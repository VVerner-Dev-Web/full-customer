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

  function updateUsageQuota(quota) {
    $('[data-quota="used"]').text(quota.used);
    $('[data-quota="granted"]').text(quota.granted);
  }
});
