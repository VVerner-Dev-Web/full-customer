/**
 * app.js — Ponto de entrada da aplicação.
 *
 * Responsabilidade única: instanciar os módulos e orquestrar
 * o carregamento inicial. Nenhuma lógica de negócio aqui.
 */
import { Chat } from "./core/Chat.js";
import { SkillManager } from "./core/SkillManager.js";
import { SkillsPage } from "./core/SkillsPage.js";
import { WelcomeService } from "./core/WelcomeService.js";
import { Tutorial } from "./core/Tutorial.js";
import { ActivateProPlugin } from "./middleware/ActivateProPlugin.js";
import { ConnectionService } from "./middleware/ConnectionService.js";
import { SimpleSkill } from "./middleware/SimpleSkill.js";
import { FragmentService } from "./utils/FragmentService.js";
import { UIManager } from "./utils/UIManager.js";

document.addEventListener("DOMContentLoaded", () => {
  const root = document.querySelector("#full-customer-root");

  Chat.attach(root);
  UIManager.attach(root);
  WelcomeService.attach(root);
  SkillsPage.attach(root);
  Tutorial.attach(root);

  const activateProMiddleware =
    ActivateProPlugin._middleware.bind(ActivateProPlugin);
  const connectionMiddleware =
    ConnectionService._middleware.bind(ConnectionService);
  const simpleSkillMiddleware = SimpleSkill._middleware.bind(SimpleSkill);

  Chat.root.addEventListener("fc/chat/ready", () => {
    SkillManager.addMiddleware(activateProMiddleware);
    SkillManager.addMiddleware(connectionMiddleware);
    SkillManager.addMiddleware(simpleSkillMiddleware);

    SkillManager.init(root);
  });

  window._refreshUI = async (requests) => {
    UIManager.toggleLoader(true);

    try {
      const data = await FragmentService.fetch(requests);

      if (!data.success) return;

      for (const req of requests) {
        const html = data.fragments[req.fragment];
        if (html && typeof req.callback === "function") {
          req.callback(html);
        }
      }

      root.dispatchEvent(new CustomEvent("fc/fragments/processed"));
    } catch (err) {
      console.error("[app] Erro ao carregar fragmentos:", err);
    } finally {
      UIManager.toggleLoader(false);
    }
  };

  window._refreshUI([
    {
      fragment: "DashboardFullPage",
      callback: (html) => {
        document.querySelector("app").innerHTML = html;
      },
    },
  ]);
});
