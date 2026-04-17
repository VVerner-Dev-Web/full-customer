document.addEventListener("DOMContentLoaded", () => {
  const root = document.querySelector("#full-customer-root");
  const appContainer = root.querySelector("app");

  async function refreshUI(requests) {
    fcLoader("show");

    const payload = {
      fragments: {},
    };

    requests.forEach((req) => {
      payload.fragments[req.fragment] = req.args || {};
    });

    try {
      const response = await fetch(`${fcData.restUrl}/fragments`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-WP-Nonce": fcData.nonce,
        },
        body: JSON.stringify(payload),
      });

      const data = await response.json();

      fcLoader("hide");

      if (data.success) {
        requests.forEach((req) => {
          const html = data.fragments[req.fragment];

          if (html && typeof req.callback === "function") {
            req.callback(html);
          }

          // Evento individual por fragmento
          root.dispatchEvent(
            new CustomEvent("full-fragment/processed", {
              detail: {
                fragment: req.fragment,
                html: html,
              },
            }),
          );
        });

        root.dispatchEvent(new CustomEvent("full-fragments/processed"));
      }
    } catch (error) {
      console.error("Erro ao processar Fragments:", error);
    }
  }

  async function fcLoader(action = "hide") {
    const el = document.querySelector("#fc-loader");
    if (!el) return;

    if (action === "show") {
      el.classList.remove("d-none");
    } else {
      el.classList.add("d-none");
    }
  }

  root.addEventListener("full-fragments/processed", () => {
    const tabs = root.querySelectorAll('[data-bs-toggle="tab"]');
    tabs.forEach((el) => {
      if (!bootstrap.Tab.getInstance(el)) {
        new bootstrap.Tab(el);
      }
    });

    const tooltips = root.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach((el) => {
      if (!bootstrap.Tooltip.getInstance(el)) {
        new bootstrap.Tooltip(el);
      }
    });

    const modals = root.querySelectorAll('[data-bs-toggle="modal"]');
    modals.forEach((el) => {
      if (!bootstrap.Modal.getInstance(el)) {
        new bootstrap.Modal(el);
      }
    });

    const popovers = root.querySelectorAll('[data-bs-toggle="popover"]');
    popovers.forEach((el) => {
      if (!bootstrap.Popover.getInstance(el)) {
        new bootstrap.Popover(el);
      }
    });
  });

  root.addEventListener("click", (e) => {
    const targetElement = e.target.closest("[data-fragment]");

    if (!targetElement) return;

    e.preventDefault();
    e.stopPropagation();

    const fragmentName = targetElement.getAttribute("data-fragment");
    const args = targetElement.dataset.args
      ? JSON.parse(targetElement.dataset.args)
      : {};

    refreshUI([
      {
        fragment: fragmentName,
        args: args,
        callback: (html) => {
          const targetSelector = targetElement.getAttribute("data-target");
          const destination = targetSelector
            ? document.querySelector(targetSelector)
            : appContainer;

          destination.innerHTML = html;
        },
      },
    ]);
  });

  refreshUI(
    [
      {
        fragment: "DashboardFullPage",
        args: {},
        callback: (html) => {
          appContainer.innerHTML = html;
        },
      },
    ],
    true,
  );
});
