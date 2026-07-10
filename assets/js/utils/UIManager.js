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
    this._reinitBootstrap();
  },

  // ─── Privado ──────────────────────────────────────────────

  _reinitBootstrap() {
    if (typeof bootstrap === "undefined") return;

    const components = [
      { sel: '[data-bs-toggle="tab"]',     Ctor: bootstrap.Tab },
      { sel: '[data-bs-toggle="tooltip"]', Ctor: bootstrap.Tooltip },
      { sel: '[data-bs-toggle="modal"]',   Ctor: bootstrap.Modal },
      { sel: '[data-bs-toggle="popover"]', Ctor: bootstrap.Popover },
    ];

    for (const { sel, Ctor } of components) {
      if (!Ctor) continue;
      this._root.querySelectorAll(sel).forEach((el) => {
        if (!Ctor.getInstance(el)) new Ctor(el);
      });
    }
  },
};
