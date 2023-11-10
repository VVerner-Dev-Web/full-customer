jQuery(function ($) {
  $("#full-images-settings").on("submit", function (e) {
    e.preventDefault();

    const $form = $(this);
    const $btn = $form.find("button");
    $btn.addClass("loading");

    $.post(ajaxurl, $form.serialize(), function (response) {
      $btn.removeClass("loading");
    });
  });

  $("#enableUploadResize").on("change", function () {
    const required = this.checked;

    $(".resize").toggleClass("hidden");

    $(".resize")
      .find("select, input")
      .each(function () {
        $(this).attr("required", required).prop("required", required);
      });
  });

  $("#full-media-replace").on("click", function () {
    const mediaFrame = wp.media({
      title: "Escolher arquivo",
      button: {
        text: "Sobrescrever",
      },
      multiple: false,
    });

    const $mediaFrameEl = $(mediaFrame.open().el);

    $mediaFrameEl.find("#menu-item-upload").click();
    $mediaFrameEl.find("#menu-item-browse").remove();

    mediaFrame.on("select", function () {
      const attachment = mediaFrame.state().get("selection").first().toJSON();

      jQuery("#full-replace-id").val(attachment.id);

      if (!$("#full-replace-id").closest(".media-modal").length) {
        $("#full-replace-id").closest("form").submit();
        $(mediaFrame.close());
      }
    });
  });
});
