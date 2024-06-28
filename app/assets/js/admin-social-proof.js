jQuery(function ($) {
  const $tabLinks = $("#analytics-view-nav a");

  $tabLinks.on("click", function (e) {
    e.preventDefault();

    const $target = $($(this).attr("href"));

    $tabLinks.not(this).removeClass("active");
    $(this).addClass("active");

    $(".analytics-view").hide();
    $target.show();
  });

  $tabLinks.first().trigger("click");

  $(".select2").select2({});
});
