jQuery(function ($) {
  const FULL = full_localize;

  $(".templately-plan-switcher button").on("click", function (e) {
    e.preventDefault();

    const filter = $(this).data("plan");

    if (!filter) {
      $(".templately-page-item").show();
      return;
    }

    $(".templately-page-item").hide();
    $(".templately-page-item[data-filter='" + filter + "']").show();
  });

  $("[data-js='insert-item']").on("click", function (e) {
    e.preventDefault();

    const item = $(this).data("item");

    Swal.fire({
      titleText: item.title,
      showConfirmButton: true,
      showDenyButton: true,
      confirmButtonText: "Inserir como página",
      denyButtonText: "Inserir como modelo",
      showLoaderOnConfirm: true,
      showLoaderOnDeny: true,
      allowOutsideClick: () => !Swal.isLoading(),
      html:
        "<p>Crie uma nova página a partir deste modelo para disponibilizá-la como uma página de rascunho em sua lista de páginas.</p>" +
        "<p>Importe este modelo para sua biblioteca para disponibilizá-lo em sua lista de modelos salvos do Elementor (abre em uma nova guia) para uso futuro.</p>",
      customClass: {
        container: "full-template-popup",
      },
      preDeny: () => installTemplateItem("template", item),
      preConfirm: () => installTemplateItem("page", item),
    }).then((response) => {
      const data = response.value;

      if (data.error) {
        Swal.fire("Ops", data.error, "error");
        return;
      }

      Swal.fire("Feito", "Template importado com sucesso!", "success");
    });
  });

  function installTemplateItem(mode, item) {
    const endpoint = "full-customer/elementor/install/" + item.id;

    return fetch(FULL.rest_url + endpoint, {
      method: "POST",
      credentials: "same-origin",
      headers: {
        "Content-Type": "application/json",
        "X-WP-Nonce": FULL.auth,
      },
      body: JSON.stringify({ mode }),
    }).then((response) => {
      return response.json();
    });
  }
});
