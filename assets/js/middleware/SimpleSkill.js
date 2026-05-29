import { Chat } from "../core/Chat.js";
import { ApiService } from "../utils/ApiService.js";

export const SimpleSkill = {
  _working: false,

  async _middleware(manager, skill, actions, msg) {
    if (actions === null) {
      return;
    }

    for (const action of actions) {
      if (!action.simpleRest) {
        continue;
      }

      const loading = Chat.sendLoadingMessage();

      try {
        const method = action.restMethod.toLowerCase();

        const response = await ApiService[method]("/" + action.restRoute);

        loading.remove();

        Chat.sendCopilotMessage(
          response.message ??
            "Ops, algo deu errado. Por favor, tente novamente.",
          response.success ? "normal" : "error",
          response.terminate === true ? true : false,
        );

        if (response.reload) {
          setTimeout(() => window.location.reload(), 1500);
        }
      } catch {
        loading.remove();
        Chat.sendCopilotMessage(
          "Ops, algo deu errado. Por favor, tente novamente.",
          "error",
        );
      }
    }
  },
};
