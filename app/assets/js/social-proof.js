jQuery(function ($) {
  let visible = false;
  let index = sessionStorage.getItem("socialProofIndex")
    ? parseInt(sessionStorage.getItem("socialProofIndex"))
    : 0;
  let data = [];

  const $recentPurchasePopup = $("#full-woo-orders-popup");
  const $recentVisitorsPopup = $("#full-woo-visitors-popup");
  const heartbeat = 5000;

  const popupContent = () => $($("#full-woo-orders-popup-template").html());

  $.get(socialProofFeed.url, function (response) {
    data = response ? response : [];
  });

  let interval = setInterval(() => {
    visible
      ? $recentPurchasePopup.addClass("visible")
      : $recentPurchasePopup.removeClass("visible");

    if (!visible) {
      const $content = popupContent();
      const item = data[index];

      Object.entries(item).forEach(([key, value]) => {
        if (value) {
          key === "image"
            ? $content.find(`[data-fragment="${key}"]`).attr("src", value)
            : $content.find(`[data-fragment="${key}"]`).text(value);
        }
      });

      $recentPurchasePopup
        .find(".full-woo-orders-popup-inner")
        .replaceWith($content);

      index = index + 1 >= data.length ? 0 : index + 1;
      sessionStorage.setItem("socialProofIndex", index);
    }

    visible = !visible;
  }, heartbeat);

  $recentPurchasePopup.on("click", ".dismiss-woo-order-popup", () => {
    clearInterval(interval);
    $recentPurchasePopup.removeClass("visible");
  });

  $recentVisitorsPopup.on("click", ".dismiss-woo-order-popup", () => {
    $recentVisitorsPopup.removeClass("visible");
    $recentPurchasePopup.removeClass("stacked");
  });

  if ($recentVisitorsPopup.length) {
    setTimeout(() => $recentVisitorsPopup.addClass("visible"), heartbeat);
  }

  if ($recentPurchasePopup.length && $recentVisitorsPopup.length) {
    $recentPurchasePopup.addClass("stacked");
  }
});
