import { Chat } from "../core/Chat.js";
import { ApiService } from "../utils/ApiService.js";

export const SimpleAction = {
  async _middleware(manager, skill, actions, msg) {
    if (actions === null) {
      return;
    }

    for (const action of actions) {
      if (!action.simpleRest) {
        continue;
      }

      try {
        const method = (action.restMethod || "post").toLowerCase();

        if (typeof ApiService[method] !== "function") {
          throw new Error(`[SimpleAction] Método HTTP não suportado: ${method}`);
        }

        let step = "";
        let state = {};
        let response;

        while (true) {
          const loading = Chat.sendLoadingMessage();

          response = await ApiService[method](
            "/" + action.restRoute,
            {
              ...(action.extraProps || {}),
              message: msg,
              step,
              state,
            }
          );

          loading.remove();

          if (!response.success) {
            Chat.sendCopilotMessage(
              response.error || response.message || "A ativação falhou.",
              "error",
              response.actions || [],
            );
            break;
          }

          const result = response.result || {};

          if (response.message) {
            Chat.sendCopilotMessage(
              response.message,
              "normal",
              response.actions || [],
            );
          }

          if (result.completed === undefined || result.completed === true) {
            if (!response.message) {
              Chat.sendCopilotMessage(
                "Ação concluída com sucesso!",
                "success",
                response.actions || [],
              );
            }

            Chat._emit("fc/simple-action/processed", {
              action,
              response,
            });

            if (response.reload) {
              setTimeout(() => window.location.reload(), 1500);
            }
            break;
          }

          step = result.step;
          state = result.state || {};
        }
      } catch (err) {
        console.error(err);
        Chat.sendCopilotMessage("Erro de conexão. Tente novamente.", "error");
      }
    }
  },
};
