import { Chat } from "./Chat.js";
import { ApiService } from "./ApiService.js";

export const ConnectionService = {
  isWaitingEmail: true,

  init() {
    document
      .querySelector("#full-customer-root")
      .addEventListener("chat/messageSent", (e) => {
        if (this.isWaitingEmail) {
          this.processEmail(e.detail.message);
        }
      });
  },

  async processEmail(rawInput) {
    const email = rawInput.toLowerCase().trim();

    const emailRegex =
      /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/;

    if (!emailRegex.test(email)) {
      Chat.sendCopilotMessage(
        "Desculpe, esse e-mail parece inválido. Certifique-se de que não há espaços ou caracteres extras.",
      );
      return;
    }

    this.isWaitingEmail = false;
    const loading = Chat.sendLoadingMessage();
    const response = await ApiService.post("/connect", { email: email });

    loading.remove();

    Chat.sendCopilotMessage(
      response.message || "Ops, algo deu errado. Por favor, tente novamente.",
      response.success ? "success" : "error",
    );

    if (!response.success) {
      this.isWaitingEmail = true;
      return;
    }

    Chat.sendLoadingMessage();

    setTimeout(() => location.reload(), 3000);
  },
};
