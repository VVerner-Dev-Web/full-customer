jQuery(function ($) {
  $("#search-images-missing-alt").on("click", function (e) {
    e.preventDefault();

    const $btn = $(this);
    $btn.addClass("loading");

    const data = {
      action: "full/ai/list-images-missing-alt",
      page: $btn.data("page"),
    };

    $.get(ajaxurl, data, function (response) {
      const { items, currentPage, totalPages, totalItems, loadedItems } =
        response.data;

      if (!Object.keys(items).length) {
        Swal.fire(
          "Feito!",
          "Todas as imagens faltando alt tags foram carregadas",
          "success"
        );

        $btn.remove();

        return;
      }

      const template = $("#template-image-missing-alt").html();

      for (const id in items) {
        let card = template.replace(/{id}/g, id).replace(/{url}/g, items[id]);
        $("#images-response").append(card);
      }

      $btn.data("page", currentPage + 1).text("Carregar mais imagens");

      if (currentPage >= totalPages) {
        $btn.remove();
      }

      $(".images-pagination").text(
        loadedItems + " de " + totalItems + " imagens carregadas"
      );

      $btn.removeClass("loading");
    });
  });

  $(document).on("keyup", ".alt-input", function () {
    const value = $(this).val().trim();
    const $container = $(this).parent();

    const $generate = $container.find(".generate-image-alt");
    const $submit = $container.find(".update-image-alt");

    if (value) {
      $generate.hide();
      $submit.show();
    } else {
      $generate.show();
      $submit.hide();
    }
  });

  $(document).on("submit", ".alt-form", function (e) {
    e.preventDefault();

    const $container = $(this);

    const data = {
      action: "full/ai/update-image-alt",
      attachmentId: $container.find(".attachmentId").val(),
      generatedContent: $container.find(".alt-input").val(),
    };

    $container.find(".update-image-alt").addClass("loading");

    $.post(ajaxurl, data, function () {
      Swal.fire("Feito!", "Imagem atualizada com sucesso", "success");
      $container.find(".update-image-alt").removeClass("loading");
    });
  });

  $(document).on("click", ".generate-image-alt", function (e) {
    e.preventDefault();

    const $btn = $(this);
    const $container = $btn.parents(".alt-form");

    const data = {
      action: "full/ai/generate-image-alt",
      attachmentId: $container.find(".attachmentId").val(),
    };

    $btn.addClass("loading");

    $.post(ajaxurl, data, function (response) {
      $btn.removeClass("loading");

      if (!response.success) {
        Swal.fire("Ops", response.data, "error");
        return;
      }

      const { content, quota } = response.data;

      updateUsageQuota(quota);

      $container.find("textarea").val(content).trigger("keyup");
    });
  });

  $.get(ajaxurl, { action: "full/ai/list-posts" }, function (response) {
    const $select = $("#postId");
    const { types, posts } = response.data;

    const options = {};

    for (const type in types) {
      options[type] = "";
    }

    for (const post of posts) {
      options[post.post_type] +=
        '<option value="' +
        post.ID +
        '">#' +
        post.ID +
        ": " +
        post.post_title +
        "</option>";
    }

    $select.html("<option></option>");

    for (const type in options) {
      let optgroup = '<optgroup label="' + types[type] + '">';
      optgroup += options[type];
      optgroup += "</optgroup>";

      $select.append(optgroup);
    }

    $select.select2({
      placeholder: "Escolha o conteúdo",
      allowClear: true,
      debug: true,
    });
  });

  $(window).on("full/form-submitted/metadescription-generator", function () {
    $("#metadesc-received").val("");

    $("#metadesc-publish").show();
    $("#metadesc-content, #metadesc-trigger").hide();
    $("#metadesc-writing").show();

    $("#metadesc-postId").val($("#postId").val());
  });

  $(window).on(
    "full/form-received/metadescription-generator",
    function (e, data) {
      if (!data.success) {
        Swal.fire("Ops", data.data, "error");
        return;
      }

      const { content, quota } = data.data;

      $("#metadesc-received").val(content);
      $("#metadesc-content").html(content);

      $("#metadesc-writing").hide();
      $("#metadesc-content, #metadesc-trigger").show();
      $("#metadesc-content").show();

      updateUsageQuota(quota);
    }
  );

  function updateUsageQuota(quota) {
    $('[data-quota="used"]').text(quota.used);
    $('[data-quota="granted"]').text(quota.granted);
  }

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
});
