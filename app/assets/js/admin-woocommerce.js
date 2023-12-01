jQuery(function ($) {
  $("#enableProductCustomTab").on("change", function () {
    const required = this.checked;

    $(".custom-tab").toggleClass("hidden");

    $(".custom-tab")
      .find("textarea, input")
      .each(function () {
        $(this).attr("required", required).prop("required", required);
      });
  });

  $("#enableWhatsAppCheckout").on("change", function () {
    const required = this.checked;

    $(".whatsapp-checkout").toggleClass("hidden");

    $(".whatsapp-checkout")
      .find("textarea, input")
      .each(function () {
        $(this).attr("required", required).prop("required", required);
      });
  });

  const phoneMaskBehavior = (val) =>
    val.replace(/\D/g, "").length === 11
      ? "(00) 00000-0000"
      : "(00) 0000-00009";

  $("#whatsappCheckoutNumber").mask(phoneMaskBehavior, {
    clearIfNotMatch: true,
    onKeyPress: function (val, e, field, options) {
      field.mask(phoneMaskBehavior.apply({}, arguments), options);
    },
  });
});
