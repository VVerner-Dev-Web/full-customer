jQuery(function ($) {
  $("#enableUploadResize").on("change", function () {
    const required = this.checked;

    $(".resize").toggleClass("hidden");

    $(".resize")
      .find("select, input")
      .each(function () {
        $(this).attr("required", required).prop("required", required);
      });
  });

  $(document).on("click", "#full-media-replace", function () {
    const mediaFrame = wp.media({
      title: "Escolher arquivo",
      button: {
        text: "Sobrescrever",
      },
      multiple: false,
    });

    const $mediaFrameEl = $(mediaFrame.open().el);

    $mediaFrameEl.find("#menu-item-upload").click();

    mediaFrame.on("select", function () {
      const attachment = mediaFrame.state().get("selection").first().toJSON();

      jQuery("#full-replace-id").val(attachment.id);

      if ($("#full-replace-id").closest(".media-modal").length) {
        $(mediaFrame.close());

        const data = {
          action: "full/widget/image-replacement",
          original: $("#full-current-id").val(),
          replace: $("#full-replace-id").val(),
        };

        $.post(wp.ajax.settings.url, data, function (response) {
          if (response.success) {
            alert("Imagem sobrescrita com sucesso!");
            return location.reload();
          } else {
            alert("Não foi possível sobrescrever a imagem");
          }
        });
      }
    });
  });
});
