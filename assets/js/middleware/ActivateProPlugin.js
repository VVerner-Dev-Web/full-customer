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

  async processInstallation(processId, pluginSlug, { progress }) {
    progress(`Realizando a instalação do plugin no seu WordPress...`);

    const res = await ApiService.post(`/actions/plugins/install/${processId}`, {
      pluginSlug: pluginSlug,
    });

    if (!res.success) {
      throw new Error(res.error || "Falha na instalação");
    }

    progress(`✅ ` + res.message);
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
