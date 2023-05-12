jQuery("document").ready(function ($) {
  const FULL = full_localize;

  insertAddSectionButton();
  initFullModal();

  const VIEWS = {
    templates: $('.full-templates[data-endpoint="templates"]').html(),
    cloud: $('.full-templates[data-endpoint="cloud"]').html(),
  };

  elementor.on("preview:loaded", function () {
    const el = elementor.$previewContents[0].body;
    $(el).on("click", ".elementor-add-full-button", function (e) {
      window.FullModal.show();
    });
  });

  elementor.on("panel:init", function () {
    $(".elementor-panel-footer-sub-menu").append(
      '<div id="elementor-panel-footer-sub-menu-item-push-full" class="elementor-panel-footer-sub-menu-item"><i class="elementor-icon eicon-cloud-upload" aria-hidden="true"></i><span class="elementor-title">' +
        "Salvar na FULL." +
        "</span></div>"
    );
  });

  elementor.hooks.addFilter(
    "elements/section/contextMenuGroups",
    filterContextMenuGroups
  );

  elementor.hooks.addFilter(
    "elements/container/contextMenuGroups",
    filterContextMenuGroups
  );

  window.FullModal.getElements("message").append(
    window.FullModal.addElement("content")
  );

  $(document).on("full-templates/imported", function () {
    window.FullModal.destroy();
  });

  $(document).on("click", ".templately-nav-item a", function (e) {
    e.preventDefault();

    const endpoint = $(this).data("endpoint");

    const container = window.FullModal.getElements("content");
    container.get(0).innerHTML = VIEWS[endpoint];

    $(document).trigger("full-templates/ready");
  });

  $(document).on(
    "click",
    "#elementor-panel-footer-sub-menu-item-push-full",
    function () {
      Swal.fire({
        titleText: "Salvar página na FULL.",
        showConfirmButton: true,
        showDenyButton: true,
        confirmButtonText: "Salvar",
        denyButtonText: "Cancelar",
        showLoaderOnConfirm: true,
        showLoaderOnDeny: true,
        allowOutsideClick: () => !Swal.isLoading(),
        html:
          "<p>Salve esta página como um modelo reutilizável em seu cloud na FULL.</p>" +
          "<p>Defina o nome do template no campo abaixo.</p>",
        input: "text",
        inputAttributes: {
          autocapitalize: "off",
          placeholder: "Nome do template",
        },
        customClass: {
          container: "full-template-popup",
        },
        preConfirm: (templateName) => {
          if (!templateName) {
            Swal.showValidationMessage("Por favor, informe o nome da página");
          }

          const endpoint = "full-customer/elementor/send-to-cloud";
          const templateContent = elementor.elements.toJSON({
            remove: ["default"],
          });

          const templateType = "section";

          return fetch(FULL.rest_url + endpoint, {
            method: "POST",
            credentials: "same-origin",
            headers: {
              "Content-Type": "application/json",
              "X-WP-Nonce": FULL.auth,
            },
            body: JSON.stringify({
              templateName,
              templateContent,
              templateType,
            }),
          }).then((response) => {
            return response.json();
          });
        },
      }).then((response) => {
        if (!response.isConfirmed) {
          return;
        }

        const data = response.value;

        if (data.error) {
          Swal.fire("Ops", data.error, "error");
          return;
        }

        Swal.fire("Feito", "Template salvo com sucesso no cloud!", "success");
      });
    }
  );

  function insertAddSectionButton() {
    const $addSectionContainer = $("#tmpl-elementor-add-section");
    const pointer = '<div class="elementor-add-section-drag-title';
    const icon =
      '<div class="elementor-add-section-area-button elementor-add-full-button" title="FULL."></div>';

    const html = $addSectionContainer.html().replace(pointer, icon + pointer);

    $addSectionContainer.html(html);
  }

  function initFullModal() {
    window.FullModal = elementorCommon.dialogsManager.createWidget("lightbox", {
      id: "full-elementor",
      headerMessage: false,
      message: "",
      hide: {
        auto: false,
        onClick: false,
        onOutsideClick: false,
        onOutsideContextMenu: false,
        onBackgroundClick: true,
        onEscKeyPress: false,
      },
      className: "elementor-templates-modal",
      closeButton: false,
      draggable: false,
      onShow: function () {
        const container = window.FullModal.getElements("content");
        container.get(0).innerHTML = VIEWS.templates;

        $(document).trigger("full-templates/ready");
      },
      onHide: function () {
        const container = window.FullModal.getElements("content");
        container.get(0).innerHTML = "";

        window.FullModal.destroy();
      },
    });
  }

  function filterContextMenuGroups(e, element) {
    const item = {
      name: "full_loripsum",
      actions: [
        {
          name: "full_loripsum",
          icon: "eicon-cloud-upload",
          title: "Salvar na FULL.",
          callback: function () {
            Swal.fire({
              titleText: "Salvar bloco na FULL.",
              showConfirmButton: true,
              showDenyButton: true,
              confirmButtonText: "Salvar",
              denyButtonText: "Cancelar",
              showLoaderOnConfirm: true,
              showLoaderOnDeny: true,
              allowOutsideClick: () => !Swal.isLoading(),
              html:
                "<p>Salve este bloco como um modelo reutilizável em seu cloud na FULL.</p>" +
                "<p>Defina o nome do template no campo abaixo.</p>",
              input: "text",
              inputAttributes: {
                autocapitalize: "off",
                placeholder: "Nome do template",
              },
              customClass: {
                container: "full-template-popup",
              },
              preConfirm: (templateName) => {
                if (!templateName) {
                  Swal.showValidationMessage(
                    "Por favor, informe o nome do bloco"
                  );
                }

                const endpoint = "full-customer/elementor/send-to-cloud";
                const templateContent = element.model.toJSON({
                  remove: ["default", "editSettings", "isLocked"],
                });

                templateContent.type = "section";

                return fetch(FULL.rest_url + endpoint, {
                  method: "POST",
                  credentials: "same-origin",
                  headers: {
                    "Content-Type": "application/json",
                    "X-WP-Nonce": FULL.auth,
                  },
                  body: JSON.stringify({ templateName, templateContent }),
                }).then((response) => {
                  return response.json();
                });
              },
            }).then((response) => {
              if (!response.isConfirmed) {
                return;
              }

              const data = response.value;

              if (data.error) {
                Swal.fire("Ops", data.error, "error");
                return;
              }

              Swal.fire(
                "Feito",
                "Template salvo com sucesso no cloud!",
                "success"
              );
            });
          },
        },
      ],
    };

    return e.splice(1, 0, item), e.join(), e;
  }
});
