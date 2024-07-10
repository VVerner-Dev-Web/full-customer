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
});
