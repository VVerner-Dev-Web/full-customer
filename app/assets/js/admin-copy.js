jQuery(function ($) {
  $("#model-text form").on("submit", function (e) {
    e.preventDefault();

    const $form = $(this);
    const $btn = $form.find("button");
    $btn.addClass("loading");

    $.post(ajaxurl, $form.serialize(), function (response) {
      location.reload();
    });
  });
});
