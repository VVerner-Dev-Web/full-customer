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

  $("#userLocation, #productThumbnail").on("change", function () {
    console.log($(this).is(":checked"));
    if (!$(this).is(":checked")) {
      return;
    }

    const sibling = $(this).is("#productThumbnail")
      ? "#userLocation"
      : "#productThumbnail";

    $(sibling).attr("checked", false).prop("checked", false);
  });
});
