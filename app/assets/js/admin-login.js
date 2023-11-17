jQuery(function ($) {
  $("#enableChangeLoginUrl").on("change", function () {
    $("#changedLoginUrl").parents("tr").toggleClass("hidden");
    $("#changedLoginUrl")
      .attr("required", this.checked)
      .prop("required", this.checked);
  });
});
