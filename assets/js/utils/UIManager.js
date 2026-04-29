/**
 * UIManager
 * Responsável por efeitos de UI transversais:
 * - loader global
 * - re-inicialização de componentes Bootstrap após injeção de HTML
 * - delegação de cliques em [data-fragment]
 */
export const UIManager = {
  _root: null,

  attach(root) {
    this._root = root;
    this._bindFragmentClicks();

    root.addEventListener("fc/fragments/processed", () => {
      this._reinitBootstrap();
    });
  },

  toggleLoader(visible = true) {
    document.querySelector("#fc-loader")?.classList.toggle("d-none", !visible);
  },

  // ─── Privado ──────────────────────────────────────────────

  _bindFragmentClicks() {
    this._root.addEventListener("click", (e) => {
      const target = e.target.closest("[data-fragment]");
      if (!target || !window._refreshUI) return;

      e.preventDefault();

      const fragment = target.getAttribute("data-fragment");
      const args = target.dataset.args ? JSON.parse(target.dataset.args) : {};
      const selector = target.getAttribute("data-target");

      window._refreshUI([
        {
          fragment,
          args,
          callback: (html) => {
            const dest = selector
              ? document.querySelector(selector)
              : this._root.querySelector("app");

            if (dest) dest.innerHTML = html;
          },
        },
      ]);
    });
  },

  _reinitBootstrap() {
    const components = [
      { sel: '[data-bs-toggle="tab"]',     Ctor: bootstrap.Tab },
      { sel: '[data-bs-toggle="tooltip"]', Ctor: bootstrap.Tooltip },
      { sel: '[data-bs-toggle="modal"]',   Ctor: bootstrap.Modal },
      { sel: '[data-bs-toggle="popover"]', Ctor: bootstrap.Popover },
    ];

    for (const { sel, Ctor } of components) {
      this._root.querySelectorAll(sel).forEach((el) => {
        if (!Ctor.getInstance(el)) new Ctor(el);
      });
    }
  },
};
