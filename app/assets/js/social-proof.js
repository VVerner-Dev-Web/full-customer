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

  const createMap = (long, lat) => {
    return new ol.Map({
      target: "full-map",
      layers: [
        new ol.layer.Tile({
          source: new ol.source.OSM(),
        }),
        new ol.layer.Vector({
          source: new ol.source.Vector({
            features: [
              new ol.Feature({
                geometry: new ol.geom.Point(ol.proj.fromLonLat([long, lat])),
              }),
            ],
          }),
          style: new ol.style.Style({
            image: new ol.style.Icon({
              anchor: [0.5, 1],
              crossOrigin: "anonymous",
              src: socialProofFeed.mapPin,
            }),
          }),
        }),
      ],
      view: new ol.View({
        constrainResolution: true,
        center: ol.proj.fromLonLat([long, lat]),
        zoom: 14,
      }),
      controls: [],
      interactions: [],
    });
  };

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

      console.log();

      if ($recentPurchasePopup.find("#full-map") && item.location) {
        const [long, lat] = item.location.split(",");
        createMap(long, lat);
      } else {
        $recentPurchasePopup.find("#full-map").hide();
      }

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

  if (typeof ol !== "undefined") {
  }
});
