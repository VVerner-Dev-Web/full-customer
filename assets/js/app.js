/**
 * app.js — Ponto de entrada da aplicação.
 *
 * Responsabilidade única: instanciar os módulos e orquestrar
 * o carregamento inicial. Nenhuma lógica de negócio aqui.
 */
import { Chat } from "./core/Chat.js";
import { CopilotManager } from "./core/CopilotManager.js";
import { SkillsPage } from "./core/SkillsPage.js";
import { WelcomeService } from "./core/WelcomeService.js";
import { Tutorial } from "./core/Tutorial.js";
import { ActivateProPlugin } from "./middleware/ActivateProPlugin.js";
import { ConnectionService } from "./middleware/ConnectionService.js";
import { SimpleAction } from "./middleware/SimpleAction.js";
import { UIManager } from "./utils/UIManager.js";

document.addEventListener("DOMContentLoaded", () => {
  const root = document.querySelector("#full-customer-root");

  Chat.attach(root);
  UIManager.attach(root);
  WelcomeService.attach(root);
  SkillsPage.attach(root);
  Tutorial.attach(root);
  ConnectionService.attach(root);

  root.addEventListener("click", (e) => {
    const btn = e.target.closest("#btnRestartChat");
    if (btn) {
      e.preventDefault();
      CopilotManager.reset();
    }
  });

  const activateProMiddleware =
    ActivateProPlugin._middleware.bind(ActivateProPlugin);
  const connectionMiddleware =
    ConnectionService._middleware.bind(ConnectionService);
  const simpleActionMiddleware = SimpleAction._middleware.bind(SimpleAction);

  CopilotManager.addMiddleware(activateProMiddleware);
  CopilotManager.addMiddleware(connectionMiddleware);
  CopilotManager.addMiddleware(simpleActionMiddleware);

  CopilotManager.init(root);
});
