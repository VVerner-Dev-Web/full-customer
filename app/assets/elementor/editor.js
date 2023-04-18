jQuery("document").ready(function ($) {
  insertAddSectionButton();
  initFullModal();

  elementor.on("preview:loaded", function () {
    const el = elementor.$previewContents[0].body;
    $(el).on("click", ".elementor-add-full-button", function (e) {
      window.FullModal.show();
    });
  });

  window.FullModal.getElements("message").append(
    window.FullModal.addElement("content")
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
      },
      position: {
        my: "center",
        at: "center",
      },
      onShow: function () {
        const container = window.FullModal.getElements("content");
        // aqui vai o conteúdo do modal com os elementos

        container.get(0).innerHTML = "<h1>Howdy</h1>";
      },
      onHide: function () {
        const container = window.FullModal.getElements("content");
        container.get(0).innerHTML = "";

        window.FullModal.destroy();
      },
    });
  }
});
