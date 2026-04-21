import { Chat } from "./modules/Chat.js";
import { FragmentService } from "./modules/FragmentService.js";
import { UIManager } from "./modules/UIManager.js";
import { WelcomeService } from "./modules/WelcomeService.js";

document.addEventListener("DOMContentLoaded", () => {
  const root = document.querySelector("#full-customer-root");

  Chat.attach(root);
  UIManager.attach(root);
  WelcomeService.attach(root);

  async function refreshUI(requests) {
    UIManager.toggleLoader(true);
    try {
      const data = await FragmentService.fetchFragments(requests);
      if (data.success) {
        requests.forEach((req) => {
          const html = data.fragments[req.fragment];
          if (html && typeof req.callback === "function") req.callback(html);
        });
        // Este evento notifica Chat e UIManager que a tela mudou
        root.dispatchEvent(new CustomEvent("full-fragments/processed"));
      }
    } catch (error) {
      console.error("Erro na navegação:", error);
    } finally {
      UIManager.toggleLoader(false);
    }
  }

  window._refreshUI = refreshUI;

  // Carga inicial
  refreshUI([
    {
      fragment: "DashboardFullPage",
      callback: (h) => (document.querySelector("app").innerHTML = h),
    },
  ]);
});
