jQuery(function ($) {
  const $container = $("#full-products-grid");
  const $cardTemplate = $("#purchase-option-card");

  const $optionsContainer = $("#purchase-options-list");
  const $optionItemTemplate = $("#purchase-option-item-list");

  let PRODUCTS = {};

  $container.on("click", ".open-purchase-options", function () {
    const id = $(this).parents(".card").data("item");
    const product = PRODUCTS[id];

    $optionsContainer.empty();

    if (1 === product.purchaseOptions.length) {
      return (location.href = product.purchaseOptions[0].url);
    }

    for (const item of product.purchaseOptions) {
      let html = $optionItemTemplate.html();

      let price = parseFloat(item.price);
      price = price.toLocaleString("pt-br", {
        style: "currency",
        currency: "BRL",
      });

      html = html.replace("{name}", item.name);
      html = html.replace("{price}", price);
      html = html.replace("{url}", item.url);

      $optionsContainer.append(html);
    }

    $.magnificPopup.open({
      items: {
        src: "#purchase-options",
        type: "inline",
      },
    });
  });

  $("#filter-products").on("keyup", function (e) {
    const value = $(this).val().trim().toLowerCase();

    $container.find(".card").show();

    if (!value) {
      return;
    }

    $container.find(".card").each(function () {
      const text = $(this).find("h3").text().toLowerCase();

      if (text.indexOf(value) === -1) {
        $(this).hide();
      }
    });
  });

  $.get(FULL.dashboard_url + "store", {}, function (response) {
    PRODUCTS = response;

    Object.entries(response).forEach(function ([id, product]) {
      let html = $cardTemplate.html();

      Object.entries(product).forEach(([key, value]) => {
        html = html.replace(new RegExp("{" + key + "}", "g"), value);
      });

      if (!product.purchaseOptions.length) {
        html = html.replace("{purchase}", "hidden");
      }

      $container.append(html);
    });
  });
});
