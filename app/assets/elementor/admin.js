(function ($) {
  "use strict";

  let canBeLoaded = true;
  const IN_ELEMENTOR = typeof window.elementor !== "undefined";

  const SWAL_SETTINGS = {
    elementor: (item) => {
      return {
        titleText: item.title,
        showConfirmButton: true,
        showDenyButton: true,
        confirmButtonText: "Inserir na página",
        denyButtonText: "Cancelar",
        showLoaderOnConfirm: true,
        showLoaderOnDeny: true,
        backdrop: true,
        allowOutsideClick: () => !Swal.isLoading(),
        html: "<p>Adicione este template na sua página agora mesmo!</p>",
        customClass: {
          container: "full-template-popup",
        },
        preConfirm: () => installTemplateItem("builder", item),
      };
    },
    admin: (item) => ({
      titleText: item.title,
      showConfirmButton: true,
      showDenyButton: true,
      confirmButtonText: "Inserir como página",
      denyButtonText: "Inserir como modelo",
      showLoaderOnConfirm: true,
      showLoaderOnDeny: true,
      backdrop: true,
      allowOutsideClick: () => !Swal.isLoading(),
      html:
        "<p>Crie uma nova página a partir deste modelo para disponibilizá-la como uma página de rascunho em sua lista de páginas.</p>" +
        "<p>Importe este modelo para sua biblioteca para disponibilizá-lo em sua lista de modelos salvos do Elementor para uso futuro.</p>",
      customClass: {
        container: "full-template-popup",
      },
      preDeny: () => installTemplateItem("template", item),
      preConfirm: () => installTemplateItem("page", item),
    }),
  };

  const resetAndFetchTemplates = () => {
    $("#response-container").data("page", 1).html("");
    fetchTemplates();
  };

  const fetchTemplates = () => {
    canBeLoaded = false;

    const type = $("#response-container").data("type");
    const page = parseInt($("#response-container").data("page"));
    const site = FULL.site_url;
    const price = getCurrentPriceFilter();
    const categories = getCurrentCategoriesFilter();

    let apiUrl = FULL.dashboard_url + "templates/" + page;

    if ("cloud" === type) {
      apiUrl = FULL.dashboard_url + "templates/cloud/" + page;
    }

    $.getJSON(apiUrl, { type, price, site, categories }, function (response) {
      $("#response-container").data("page", page + 1);

      if (1 === page && !response.items.length) {
        $("#no-items").show();
        return;
      }

      $("#no-items").hide();

      for (const item of response.items) {
        const html = parseTemplateHtml(item);
        $("#response-container").append(html);
      }

      canBeLoaded = response.totalPages > response.currentPage;
    });
  };

  const parseTemplateHtml = (item) => {
    const selector =
      "cloud" === item.origin
        ? "#tpl-templately-cloud-item"
        : "#tpl-templately-item";
    let html = $(selector).html();

    Object.entries(item).forEach((data) => {
      const [key, value] = data;
      html = html.replace(new RegExp("{" + key + "}", "g"), value);
    });

    let buttonHtml = item?.canBeInstalled
      ? $("#tpl-button-insert-item").html()
      : $("#tpl-button-purchase-item").html();

    buttonHtml = buttonHtml?.replace("{purchaseUrl}", item.purchaseUrl);

    html = html.replace("{button}", buttonHtml);
    html = html.replace(/{json}/g, JSON.stringify(item));

    return html;
  };

  const getCurrentPriceFilter = () => {
    return $(".templately-plan-switcher button.active").data("plan");
  };

  const getCurrentCategoriesFilter = () => {
    const categories = [];

    $("#full-template-filter input:checked").each(function () {
      categories.push($(this).val());
    });

    return categories;
  };

  const deleteCloudItem = (item) => {
    const endpoint = "full-customer/elementor/delete-from-cloud/" + item.id;

    fetch(FULL.rest_url + endpoint, {
      method: "POST",
      credentials: "same-origin",
      headers: {
        "Content-Type": "application/json",
        "X-WP-Nonce": FULL.auth,
      },
    }).then((response) => {
      return response.json();
    });
  };

  const installTemplateItem = (mode, item) => {
    const endpoint = "full-customer/elementor/install/";

    return fetch(FULL.rest_url + endpoint, {
      method: "POST",
      credentials: "same-origin",
      headers: {
        "Content-Type": "application/json",
        "X-WP-Nonce": FULL.auth,
      },
      body: JSON.stringify({ mode, item }),
    }).then((response) => {
      return response.json();
    });
  };

  const bindScrollEvent = () => {
    if (IN_ELEMENTOR) {
      $(".templately-contents").on("scroll", function () {
        const scrollContainer = $(".templately-contents")[0];

        const clientHeight = document
          .querySelector(".full-templates-admin-body")
          .getBoundingClientRect().height;

        const scrollHeight = scrollContainer.scrollHeight;
        const scrollTop = scrollContainer.scrollTop;

        const offset = scrollHeight * 0.15;

        const reachBottom = scrollHeight - offset <= clientHeight + scrollTop;

        if (reachBottom && canBeLoaded) {
          fetchTemplates();
        }
      });
    } else {
      $(window).on("scroll", function () {
        const clientHeight = jQuery(window).height();

        const scrollHeight = jQuery(document).height();
        const scrollTop = jQuery(document).scrollTop();

        const offset = scrollHeight * 0.15;

        const reachBottom = scrollHeight - offset <= clientHeight + scrollTop;

        if (reachBottom && canBeLoaded) {
          fetchTemplates();
        }
      });
    }
  };

  const getTemplatePositionToInsert = () => {
    let at = -1;

    const children = elementor
      .getPreviewContainer()
      .view.getChildViewContainer()
      .children();

    for (const child of children) {
      console.log(child.dataset.view);
      at++;

      if ("choose-action" === child.dataset.view) {
        break;
      }
    }

    return at;
  };

  const addTemplateToElementorBuilder = (template) => {
    for (let i = 0; i < template.content.length; i++) {
      window.$e.run("document/elements/create", {
        container: window.elementor.getPreviewContainer(),
        model: template.content[i],
        options: {
          at: getTemplatePositionToInsert(),
          withPageSettings: null,
        },
      });
    }

    $(document).trigger("full-templates/imported");
  };

  $(document).on(
    "change",
    "#full-template-filter input",
    resetAndFetchTemplates
  );

  $(document).on("click", ".templately-plan-switcher button", function (e) {
    e.preventDefault();

    $(".templately-plan-switcher button").removeClass("active");
    $(this).addClass("active");

    resetAndFetchTemplates();
  });

  $(document).on("click", "[data-js='insert-item']", function (e) {
    e.preventDefault();

    const item = $(this).data("item");

    Swal.fire(
      IN_ELEMENTOR ? SWAL_SETTINGS.elementor(item) : SWAL_SETTINGS.admin(item)
    ).then((response) => {
      if (response.isDismissed) {
        return;
      }

      const data = response.value;

      if (data.error) {
        Swal.fire("Ops", data.error, "error");
        return;
      }

      if (!IN_ELEMENTOR) {
        Swal.fire("Feito", "Template importado com sucesso!", "success");
        return;
      }

      if (response.isConfirmed) {
        addTemplateToElementorBuilder(data.builder);

        // fechar trqecuino de import
      }
    });
  });

  $(document).on("click", "[data-js='buy-item']", function (e) {
    e.preventDefault();

    location.href = $(this).data("href")
      ? $(this).data("href")
      : FULL.store_url;
  });

  $(document).on("click", '[data-js="send-to-cloud"]', function (e) {
    e.preventDefault();

    const $el = $(this);

    const endpoint =
      "full-customer/elementor/send-to-cloud/" + $el.data("post");

    $el.attr("disabled", true).text("Enviando...");

    fetch(FULL.rest_url + endpoint, {
      method: "POST",
      credentials: "same-origin",
      headers: {
        "Content-Type": "application/json",
        "X-WP-Nonce": FULL.auth,
      },
    })
      .then((response) => response.json())
      .then((data) => {
        $el.replaceWith(data.button);
      });
  });

  $(document).on("click", '[data-js="delete-from-cloud"]', function (e) {
    e.preventDefault();

    const $el = $(this);

    Swal.fire({
      titleText: "Excluir template " + $el.data("item").title,
      showConfirmButton: true,
      showDenyButton: true,
      confirmButtonText: "Voltar",
      denyButtonText: "Excluir",
      showLoaderOnDeny: true,
      backdrop: true,
      allowOutsideClick: () => !Swal.isLoading(),
      html:
        "<p>Tem certeza que quer excluir este template?</p>" +
        "<p>Após excluí-lo, o template só ficará disponível dos sites em que ele foi instalado anteriormente.</p>",
      customClass: {
        container: "full-template-popup",
      },
      preDeny: () => deleteCloudItem($el.data("item")),
    }).then((response) => {
      if (!response.isDenied) {
        return;
      }

      const data = response.value;

      if (data.error) {
        Swal.fire("Ops", data.error, "error");
        return;
      }

      Swal.fire("Feito", "Template excluído com sucesso!", "success");

      $el.parents(".single-cloud-item").remove();

      if (!$(".single-cloud-item").length) {
        $("#no-items").show();
      }
    });
  });

  $(document).on(
    "click",
    '[data-js="sync-cloud-template"]:not(.syncing-full-cloud)',
    function (e) {
      e.preventDefault();

      $(this).addClass("syncing-full-cloud");

      const endpoint = "full-customer/elementor/sync";

      fetch(FULL.rest_url + endpoint, {
        method: "POST",
        credentials: "same-origin",
        headers: {
          "Content-Type": "application/json",
          "X-WP-Nonce": FULL.auth,
        },
      }).then((response) => {
        $(this).removeClass("syncing-full-cloud");

        Swal.fire({
          titleText: "Cache excluído",
          confirmButtonText: "Obrigado",
          showLoaderOnDeny: true,
          html: '<p>Pronto! Todos os caches relacionados a biblioteca foram limpos e seus modelos serão verificados na próxima vez que você acessar a página de "Modelos" do Elementor</p>',
          customClass: {
            container: "full-template-popup",
          },
          preDeny: () => deleteCloudItem($el.data("item")),
        });
      });
    }
  );

  $(document).on("full-templates/ready", function () {
    resetAndFetchTemplates();
    bindScrollEvent();
  });

  if ($("#response-container").length) {
    resetAndFetchTemplates();
    bindScrollEvent();
  }
})(jQuery);
