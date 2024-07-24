jQuery(function ($) {
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
});
