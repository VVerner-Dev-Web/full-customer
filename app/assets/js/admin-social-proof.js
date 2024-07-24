jQuery(function ($) {
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
