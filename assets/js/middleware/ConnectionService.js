import { Chat } from "../core/Chat.js";
import { CopilotManager } from "../core/CopilotManager.js";
import { ApiService } from "../utils/ApiService.js";

const EMAIL_REGEX =
  /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/;

export const ConnectionService = {
  _working: false,
  _waitingEmail: false,

  attach(root) {
    root.addEventListener("fc/chat/reset", () => {
      this.reset();
    });
  },

  reset() {
    this._working = false;
    this._waitingEmail = false;
    CopilotManager.waitingUserPersonalAnswer = false;
  },

  async _middleware(manager, skill, actions, msg) {
    if (skill.id !== "connect") {
      return;
    }

    this._working = true;

    const action = actions === null ? null : actions[0];

    if (action?.id !== "connectAccount" && !this._waitingEmail) {
      return;
    }

    if (!this._waitingEmail) {
      const emailVal = msg ? msg.trim() : "";
      if (!emailVal) {
        CopilotManager.waitingUserPersonalAnswer = true;
        this._waitingEmail = true;
        Chat.sendCopilotMessage(
          "Por favor, insira o e-mail utilizado durante a compra das licenças na FULL.",
        );
        return;
      }
      this._waitingEmail = true;
    }

    const email = (msg || "").toLowerCase().trim();

    if (!EMAIL_REGEX.test(email)) {
      Chat.sendCopilotMessage(
        "Desculpe, esse e-mail parece inválido. Certifique-se de que não há espaços ou caracteres extras.",
      );
      return;
    }

    const loading = Chat.sendLoadingMessage();

    try {
      const response = await ApiService.post("/actions/account/connect", {
        email,
      });
      loading.remove();

      Chat.sendCopilotMessage(
        response.message ?? "Ops, algo deu errado. Por favor, tente novamente.",
        response.success ? "success" : "error",
      );

      if (!response.success) return;

      Chat.sendLoadingMessage();
      if (window.fcData) {
        window.fcData.connected = true;
      }
      this.reset();
      setTimeout(() => {
        CopilotManager.reset();
      }, 1000);
    } catch {
      loading.remove();
      Chat.sendCopilotMessage("Erro de conexão. Tente novamente.", "error");
    }
  },
};
