export const UIManager = {
  root: null,

  attach(root) {
    this.root = root;
    this._bindEvents();

    this.root.addEventListener("full-fragments/processed", () => {
      this.reinitBootstrapComponents();
    });
  },

  _bindEvents() {
    // Captura cliques em data-fragments em qualquer lugar do root
    this.root.addEventListener("click", (e) => {
      const target = e.target.closest("[data-fragment]");
      if (!target) return;

      e.preventDefault();
      const fragment = target.getAttribute("data-fragment");
      const args = target.dataset.args ? JSON.parse(target.dataset.args) : {};
      const sel = target.getAttribute("data-target");

      if (window._refreshUI) {
        window._refreshUI([
          {
            fragment,
            args,
            callback: (html) => {
              const dest = sel
                ? document.querySelector(sel)
                : this.root.querySelector("app");
              if (dest) dest.innerHTML = html;
            },
          },
        ]);
      }
    });
  },

  toggleLoader(show = true) {
    const el = document.querySelector("#fc-loader");
    if (el) el.classList.toggle("d-none", !show);
  },

  reinitBootstrapComponents() {
    const config = [
      { sel: '[data-bs-toggle="tab"]', class: bootstrap.Tab },
      { sel: '[data-bs-toggle="tooltip"]', class: bootstrap.Tooltip },
      { sel: '[data-bs-toggle="modal"]', class: bootstrap.Modal },
      { sel: '[data-bs-toggle="popover"]', class: bootstrap.Popover },
    ];

    config.forEach((item) => {
      this.root.querySelectorAll(item.sel).forEach((el) => {
        if (!item.class.getInstance(el)) new item.class(el);
      });
    });
  },
};
