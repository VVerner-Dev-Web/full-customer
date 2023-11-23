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
});
