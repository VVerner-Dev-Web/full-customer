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
