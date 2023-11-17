jQuery(function ($) {
  $("#full-email-test").on("submit", function (e) {
    e.preventDefault();

    const $form = $(this);
    const $btn = $form.find("button");
    $btn.addClass("loading");

    $.post(ajaxurl, $form.serialize(), function (response) {
      Swal.fire(
        "Feito",
        "Tentativa de envio realizada, verifique seu e-mail e a caixa de logs para mais detalhes",
        "info"
      );
      $btn.removeClass("loading");
    });
  });

  $("#enableSmtp").on("change", function () {
    const required = this.checked;

    $(".smtp").toggleClass("hidden");

    $(".smtp")
      .find("select, input")
      .each(function () {
        $(this).attr("required", required).prop("required", required);
      });
  });
});
