jQuery(function ($) {
  let visible = false;
  let index = sessionStorage.getItem("socialProofIndex") ?? 0;
  let data = [];

  $.get(socialProofFeed, function (response) {
    data = response;
  });

  const $popup = $("#full-woo-orders-popup");
  const heartbeat = 5000;

  const popupContent = () => $($("#full-woo-orders-popup-template").html());

  let interval = setInterval(() => {
    visible ? $popup.addClass("visible") : $popup.removeClass("visible");

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

      $popup.find(".full-woo-orders-popup-inner").replaceWith($content);

      index = index + 1 >= data.length ? 0 : index + 1;
      sessionStorage.setItem("socialProofIndex", index);
    }

    visible = !visible;
  }, heartbeat);

  $popup.on("click", ".dismiss-woo-order-popup", () => {
    clearInterval(interval);
    $popup.removeClass("visible");
  });
});
