const codeItems = document.querySelectorAll(".codemirror-code");

codeItems.forEach((el) => {
  const codemirror = CodeMirror.fromTextArea(el, {
    mode: el.dataset.mode,
    lineNumbers: true,
    lineWrapping: true,
    tabSize: 2,
  });

  codemirror.setSize("100%", 300);

  codemirror.on("change", function (instance, obj) {
    jQuery(el)
      .parents("form")
      .find(".codemirror-code-value")
      .val(instance.getValue());
  });
});

const debugActivated = () => jQuery("#enableWpDebug").is(":checked");

jQuery(".requireWpDebug").on("change", function () {
  if (jQuery(this).is(":checked") && !debugActivated()) {
    jQuery("#enableWpDebug")
      .attr("checked", true)
      .prop("checked", true)
      .trigger("change");

    Swal.fire(
      "Atenção!",
      "Para ativar este recurso você precisa manter o WP Debug ativo, vamos atualizar a configuração para você",
      "info"
    );
  }
});

jQuery("#enableWpDebug").on("change", function () {
  if (jQuery(this).is(":checked")) {
    return;
  }

  jQuery(".requireWpDebug")
    .attr("checked", false)
    .prop("checked", false)
    .trigger("change");
});

jQuery("#enableWpDebugLog").on("change", function () {
  jQuery(".show-logs").toggleClass("hidden");
});
