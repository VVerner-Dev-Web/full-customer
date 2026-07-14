import { Chat } from "../core/Chat.js";
import { ApiService } from "../utils/ApiService.js";
import { generateId } from "../utils/functions.js";

export const ActivateProPlugin = {
  _working: false,

  async _middleware(manager, skill, actions, msg) {
    if (skill.id !== "activateProPlugin") {
      return;
    }

    this._working = true;
    const workingPlugins = actions;

    for (const plugin of workingPlugins) {
      if (plugin.simpleRest) {
        continue;
      }

      const processId = generateId();

      const callbacks = {
        start: () =>
          Chat.sendCopilotMessage(`Começando processo para: ${plugin.name}`),
        progress: (msg) => Chat.sendCopilotMessage(msg),
        onSuccess: (msg) => Chat.sendCopilotMessage(msg, "success", false, [
          {
            label: 'Reiniciar chat',
            action: 'restart-chat',
          },
          {
            label: 'Recarregar página',
            action: 'reload',
          }
        ]),
        onError: (msg) => Chat.sendCopilotMessage(msg, "error", false, [
          {
            label: 'Suporte',
            action: 'help',
          },
          {
            label: 'Reiniciar chat',
            action: 'restart-chat',
          },
          {
            label: 'Recarregar página',
            action: 'reload',
          }
        ]),
      };

      const stopPolling = this._startPolling(processId, callbacks.progress);

      try {
        callbacks.start();
        await this.processFullActivation(
          processId,
          plugin.extraProps.pluginSlug,
          callbacks,
        );
        await this.processInstallation(
          processId,
          plugin.extraProps.pluginSlug,
          callbacks,
        );
        await this.processWordPressActivation(
          processId,
          plugin.extraProps.plugin,
          callbacks,
        );
        await this.processLicense(
          processId,
          plugin.extraProps.pluginSlug,
          callbacks,
        );
        callbacks.onSuccess(`🚀 Concluído com sucesso!`);
        await manager._loadAndRenderSkills();
      } catch (err) {
        callbacks.onError(`❌ Erro: ${err.message}`);
      } finally {
        stopPolling();
      }
    }

    this._working = false;
  },

  _startPolling(processId, onProgress) {
    let _lastState = null;
    const { restUrl, nonce } = window.fcData;

    let isPolling = true;
    let timerId = null;

    const poll = async () => {
      if (!isPolling) return;

      try {
        const res = await fetch(`${restUrl}/actions/execution/${processId}`, {
          headers: {
            "X-WP-Nonce": nonce,
          },
        });
        if (res.ok) {
          const data = await res.json();
          const states = data.states || [];

          for (const statusText of states) {
            if (statusText && _lastState !== statusText) {
              _lastState = statusText;
              onProgress(statusText);
            }
          }
        }
      } catch (e) {
        console.warn("[Status Polling] Erro ao buscar status de execução", e);
      }

      if (isPolling) {
        timerId = setTimeout(poll, 1000);
      }
    };

    poll();

    return () => {
      isPolling = false;
      if (timerId) {
        clearTimeout(timerId);
      }
    };
  },

  // ─── Processos ────────────────────────────────────────────

  async processFullActivation(processId, pluginSlug, { progress }) {
    progress(`Solicitando ativação no painel da FULL`);

    const res = await ApiService.post(
      `/actions/plugins/full-activate/${processId}`,
      {
        pluginSlug: pluginSlug,
      },
    );

    if (!res.success) {
      throw new Error(res.error || "Falha na instalação");
    }

    progress(`✅ ` + res.message);
  },

  async processInstallation(processId, pluginSlug, options) {
    const { progress } = options;
    progress(`Buscando dados do plugin no repositório...`);

    // 1. Busca dados do plugin e estado local
    const infoRes = await ApiService.get(`/actions/plugins/info/${pluginSlug}`);
    if (!infoRes.success || !infoRes.data?.package) {
      throw new Error(infoRes.error || "Não foi possível obter dados do plugin.");
    }

    const pluginData = infoRes.data;

    // 2. Se o plugin já estiver instalado localmente e atualizado
    if (pluginData.local?.installed && pluginData.local?.upToDate) {
      progress(`Plugin já instalado localmente na versão mais recente. Validando dependências...`);
      
      const installRes = await ApiService.post(`/actions/plugins/install/${processId}`, {
        pluginSlug: pluginSlug,
      });

      if (!installRes.success) {
        throw new Error(installRes.error || "Falha na validação final da instalação.");
      }

      progress(`✅ ` + installRes.message);
      return;
    }

    // 3. Caso contrário, executa o download pelo navegador
    progress(`Iniciando download do plugin pelo seu navegador...`);

    const response = await fetch(pluginData.package);
    if (!response.ok) {
      throw new Error(`Falha ao baixar o plugin (${response.statusText})`);
    }

    let downloadedBytes = 0;
    const reader = response.body.getReader();
    const chunks = [];

    while (true) {
      const { done, value } = await reader.read();
      if (done) break;

      chunks.push(value);
      downloadedBytes += value.length;

      const loadedMb = (downloadedBytes / (1024 * 1024)).toFixed(2);
      progress(`Baixando arquivo do plugin... (${loadedMb} MB baixados)`);
    }

    const blob = new Blob(chunks);
    progress(`Download concluído! Preparando para enviar...`);

    // 4. Fatiamento em chunks de 1MB e upload para o servidor
    const chunkSize = 1024 * 1024; // 1MB por chunk
    const totalChunks = Math.ceil(blob.size / chunkSize);
    const fileName = `${pluginSlug}.zip`;

    for (let i = 0; i < totalChunks; i++) {
      const start = i * chunkSize;
      const end = Math.min(start + chunkSize, blob.size);
      const chunkSlice = blob.slice(start, end);

      const formData = new FormData();
      formData.append("chunk", chunkSlice, fileName);
      formData.append("fileName", fileName);
      formData.append("chunkIndex", i);
      formData.append("totalChunks", totalChunks);
      formData.append("pluginSlug", pluginSlug);

      const uploadPercent = Math.round((i / totalChunks) * 100);
      if (options.isStaff) {
        progress(`Enviando arquivo do plugin... (${uploadPercent}%)`);
      }
      Chat.sendProgressMessage("upload-plugin", "Enviando arquivo do plugin...", uploadPercent);

      const uploadRes = await ApiService.postFormData(
        `/actions/plugins/upload-chunk/${processId}`,
        formData,
      );

      if (!uploadRes.success) {
        throw new Error(uploadRes.error || "Falha ao enviar pedaço do plugin.");
      }
      
      if (uploadRes.completed) {
        Chat.sendProgressMessage("upload-plugin", "Enviando arquivo do plugin...", 100);
        if (options.isStaff) {
          progress(`Enviando arquivo do plugin... (100%)`);
        }
      }
    }

    // 5. Instalação física e resolução de dependências no backend
    progress(`Descompactando e finalizando instalação no seu WordPress...`);

    const installRes = await ApiService.post(`/actions/plugins/install/${processId}`, {
      pluginSlug: pluginSlug,
    });

    if (!installRes.success) {
      throw new Error(installRes.error || "Falha ao finalizar a instalação do plugin.");
    }

    progress(`✅ ` + installRes.message);
  },

  async processWordPressActivation(processId, plugin, { progress }) {
    progress(`Ativando plugin no seu WordPress...`);

    const res = await ApiService.post(
      `/actions/plugins/wordpress-activate/${processId}`,
      {
        plugin: plugin,
      },
    );

    if (!res.success) {
      throw new Error(res.error || "Falha na instalação");
    }

    progress(`✅ ` + res.message);
  },

  async processLicense(processId, pluginSlug, { progress }) {
    progress(`E agora vamos inserir a licença oficial do plugin...`);

    const res = await ApiService.post(`/actions/plugins/license/${processId}`, {
      pluginSlug: pluginSlug,
    });

    if (!res.success) {
      throw new Error(res.error || "Falha na ativação");
    }

    progress(`✅ ` + res.message);

    if (res.result?.redirectUrl) {
      progress(`Redirecionando...`);
      window.location.href = res.result.redirectUrl;
    }
  },
};
