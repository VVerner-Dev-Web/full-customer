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

    $select.html("<option hidden>Selecione o conteúdo</option>");

    for (const type in options) {
      let optgroup = '<optgroup label="' + types[type] + '">';
      optgroup += options[type];
      optgroup += "</optgroup>";

      $select.append(optgroup);
    }
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
});
