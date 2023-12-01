jQuery(function ($) {
  $(window).on("full/form-submitted/copywrite-generator", function () {
    $("#copywrite-publish #post_title").val("");
    $("#copywrite-publish #post_content").val("");

    $("#copywrite-publish").show();
    $("#generated-content, #publish-trigger").hide();
    $("#copywrite-writing").show();
  });

  $(window).on("full/form-received/copywrite-generator", function (e, data) {
    if (!data.success) {
      Swal.fire("Ops", data.data, "error");
      return;
    }

    const { title, content, quota } = data.data;

    $("#copywrite-publish #post_title").val(title);
    $("#copywrite-publish #post_content").val(content);

    $("#generated-content").html("<h1>" + title + "</h1>" + content);

    $("#copywrite-writing").hide();
    $("#generated-content, #publish-trigger").show();
    $("#generated-content").show();

    updateUsageQuota(quota);
  });

  $(window).on("full/form-received/copywrite-publish", function (e, data) {
    const { success } = data;
    Swal.fire({
      icon: success ? "success" : "error",
      titleText: success ? "Feito!" : "ops",
      text: success ? "Post criado com sucesso!" : data.data,
      showConfirmButton: true,
      confirmButtonText: success ? "Acessar post" : "Tentar novamente",
      showLoaderOnConfirm: true,
      backdrop: true,
      allowOutsideClick: () => !Swal.isLoading(),
      preConfirm: () => {
        if (!success) {
          return;
        }

        location.href = data.data.replace("&amp;", "&");
      },
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

  function updateUsageQuota(quota) {
    $('[data-quota="used"]').text(quota.used);
    $('[data-quota="granted"]').text(quota.granted);
  }
});
