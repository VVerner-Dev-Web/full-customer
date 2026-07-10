import { CopilotManager } from "./CopilotManager.js";

export const SkillsPage = {
  _root: null,
  _container: null,

  attach(root) {
    this._root = root;

    const container = this._root.querySelector("#conteudoAbas");
    if (!container) return;

    this._container = container;
    this.bindEvents();
  },

  bindEvents() {
    this._container.addEventListener("click", (e) => {
      const btn = e.target.closest(".fs-btn-ativar");
      if (!btn) return;

      try {
        const trigger = JSON.parse(btn.dataset.trigger);
        CopilotManager.trigger(trigger.skill, trigger.agent, trigger.prompt);
      } catch (error) {
        console.error("Failed to parse action dataset:", error);
      }
    });
  },
};
