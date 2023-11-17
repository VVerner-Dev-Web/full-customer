const codeItems = document.querySelectorAll(".codemirror-code");

codeItems.forEach((el) => {
  const codemirror = CodeMirror.fromTextArea(el, {
    mode: el.dataset.mode,
    lineNumbers: true,
    lineWrapping: true,
    tabSize: 2,
  });

  codemirror.setSize("100%", 300);
});

jQuery(".full-widget-form").on("submit", function (e) {
  e.preventDefault();

  const $form = jQuery(this);
  const $btn = $form.find("button");
  $btn.addClass("loading");

  jQuery.post(ajaxurl, $form.serialize(), function (response) {
    $btn.removeClass("loading");
  });
});
