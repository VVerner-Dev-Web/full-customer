import { SkillManager } from "./SkillManager";

export const SkillsPage = {
  _root: null,
  _container: null,

  attach(root) {
    this._root = root;

    root.addEventListener("fc/fragments/processed", () => {
      const container = this._root.querySelector("#conteudoAbas");

      if (!container) return;

      this._container = container;
      this.bindEvents();
    });
  },

  bindEvents() {
    this._container.querySelectorAll(".fs-btn-ativar").forEach((btn) => {
      btn.addEventListener("click", () => {
        try {
          const trigger = JSON.parse(btn.dataset.trigger);

          window._refreshUI([
            {
              fragment: "DashboardFullPage",
              callback: (html) => {
                this._root.addEventListener(
                  "fc/chat/ready",
                  () => {
                    SkillManager.trigger(trigger.skill, trigger.prompt);
                    // console.log("oi?");
                  },
                  { once: true },
                );

                document.querySelector("app").innerHTML = html;
              },
            },
          ]);
        } catch (error) {
          console.error("Failed to parse action dataset:", error);
        }
      });
    });
  },
};
