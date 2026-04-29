import { Chat } from "../core/Chat.js";
import { SkillManager } from "../core/SkillManager.js";
import { ApiService } from "../utils/ApiService.js";

const EMAIL_REGEX =
  /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/;

export const ConnectionService = {
  _working: false,
  _waitingEmail: false,

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
      SkillManager.waitingUserPersonalAnswer = true;

      this._waitingEmail = true;

      Chat.sendCopilotMessage(
        "Por favor, insira o e-mail utilizado durante a compra das licenças na FULL.",
      );

      return;
    }

    const email = msg.toLowerCase().trim();

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
      setTimeout(() => location.reload(), 3000);
    } catch {
      loading.remove();
      Chat.sendCopilotMessage("Erro de conexão. Tente novamente.", "error");
    }
  },
};
