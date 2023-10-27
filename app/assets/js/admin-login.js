jQuery(function ($) {
  $("#full-login-settings").on("submit", function (e) {
    e.preventDefault();

    const $form = $(this);
    const $btn = $form.find("button");
    $btn.addClass("loading");

    $.post(ajaxurl, $form.serialize(), function (response) {
      console.log(response);
      $btn.removeClass("loading");
    });
  });

  $("#enableChangeLoginUrl").on("change", function () {
    $("#changedLoginUrl").parents("tr").toggleClass("hidden");
    $("#changedLoginUrl")
      .attr("required", this.checked)
      .prop("required", this.checked);
  });
});
