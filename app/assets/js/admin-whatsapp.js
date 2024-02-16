jQuery(function ($) {
  $("#enableGlobalButton").on("change", function () {
    const required = this.checked;

    $(".whatsapp").toggleClass("hidden");

    $(".whatsapp")
      .find("select, input")
      .each(function () {
        $(this).attr("required", required).prop("required", required);
      });
  });

  const SPMaskBehavior = (val) =>
    val.replace(/\D/g, "").length === 11
      ? "(00) 00000-0000"
      : "(00) 0000-00009";

  $("#whatsappNumber").mask(SPMaskBehavior, {
    clearIfNotMatch: true,
    onKeyPress: function (val, e, field, options) {
      field.mask(SPMaskBehavior.apply({}, arguments), options);
    },
  });
});
