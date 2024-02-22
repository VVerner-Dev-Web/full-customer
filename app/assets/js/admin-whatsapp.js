jQuery(function ($) {
  $("#enableGlobalButton").on("change", function () {
    const required = this.checked;

    $(".whatsapp").toggleClass("hidden");

    $(".whatsapp")
      .find("select, input")
      .each(function () {
        $(this).attr("required", required).prop("required", required);
      });

    $("#displayCondition").val("global").trigger("change");
  });

  $("#displayCondition").on("change", function () {
    const showingForCpt = "cpt" === $(this).val();

    showingForCpt
      ? $(".displayConditionCpt").removeClass("hidden")
      : $(".displayConditionCpt").addClass("hidden");
  });

  const SPMaskBehavior = (val) =>
    val.replace(/\D/g, "").length === 11
      ? "(00) 00000-0000"
      : "(00) 0000-00009";

  $("#whatsappNumber, #full-whatsappNumber").mask(SPMaskBehavior, {
    clearIfNotMatch: true,
    onKeyPress: function (val, e, field, options) {
      field.mask(SPMaskBehavior.apply({}, arguments), options);
    },
  });
});
