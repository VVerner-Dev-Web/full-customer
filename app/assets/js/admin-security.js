jQuery(function ($) {
  $("#enablePasswordProtection").on("change", function () {
    const required = this.checked;

    $(".password").toggleClass("hidden");

    $(".password")
      .find("input")
      .each(function () {
        $(this).attr("required", required).prop("required", required);
      });
  });
});
