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
        onSuccess: (msg) => Chat.sendCopilotMessage(msg, "success", true),
        onError: (msg) => Chat.sendCopilotMessage(msg, "error", true),
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
    let _isPolling = false;

    const interval = setInterval(async () => {
      if (_isPolling) return;

      _isPolling = true;

      try {
        const res = await ApiService.post(`/actions/execution/${processId}`);
        const statusText = res.state;

        if (statusText && _lastState !== statusText) {
          _lastState = statusText;
          onProgress(statusText);
        }
      } catch (e) {
        console.warn("[Status Fetch] Erro na requisição de polling", e);
      } finally {
        _isPolling = false;
      }
    }, 2000);

    return () => clearInterval(interval);
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
      authorizationCookies: fcData.authorizationCookies,
    });

    if (!res.success) {
      throw new Error(res.error || "Falha na ativação");
    }

    progress(`✅ ` + res.message);
  },
};
