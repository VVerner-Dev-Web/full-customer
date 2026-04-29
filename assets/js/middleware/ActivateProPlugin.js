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
        await this.processInstallation(
          processId,
          plugin.extraProps.plugin,
          callbacks,
        );
        await this.processActivation(
          processId,
          plugin.extraProps.plugin,
          callbacks,
        );
        await this.processLicense(
          processId,
          plugin.extraProps.plugin,
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

  async processInstallation(processId, pluginSlug, { progress }) {
    progress(`Preparando ambiente...`);

    const res = await ApiService.post(`/actions/plugins/install/${processId}`, {
      plugin: pluginSlug,
    });

    if (!res.success) {
      throw new Error(res.error || "Falha na instalação");
    }

    progress(`✅ Instalado.`);
  },

  async processActivation(processId, pluginSlug, { progress }) {
    progress(`Ativando plugin...`);

    await ApiService.post(`/actions/plugins/activate/${processId}`, {
      plugin: pluginSlug,
    });
  },

  async processLicense(processId, pluginSlug, { progress }) {
    progress(`Ativando licença...`);

    const res = await ApiService.post(`/actions/plugins/license/${processId}`, {
      plugin: pluginSlug,
      authorizationCookies: fcData.authorizationCookies,
    });

    if (!res.success) {
      throw new Error(res.error || "Falha na ativação");
    }
  },
};
