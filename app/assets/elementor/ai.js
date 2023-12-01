(function ($) {
  let TW_LETTER_INDEX = 0;
  let TW_SOURCE = [];
  let TW_TIMEOUT = null;

  let CURRENT_PANEL = null;
  let CURRENT_WIDGET = null;

  let IA_MODE = null;
  let IA_TEMPLATES = [];
  let IA_TEMPLATES_FETCH = false;

  const validWidgets = ["heading", "text-editor"];
  const ELEMENTS = {
    promptContainer: "#prompt-message",
    input: "#prompt-message textarea",
    alert: "#prompt-alert",
    loader: "#prompt-loader",
    responseContainer: "#prompt-response",
    responseContent: "#prompt-response-content",
    generateTrigger: "#prompt-message button",
    redoTrigger: '#prompt-actions [data-action="redo"]',
    copyTrigger: '#prompt-actions [data-action="copy"]',
    insertTrigger: '#prompt-actions [data-action="insert"]',
    promptTemplate: ".single-prompt-template",
    templatesContainer: "#prompt-templates",
    iaModeLegend: ".ai-mode",
    iaModeChange: ".ai-mode a",
  };

  const initFullIaModal = () => {
    window.FullIaModal = elementorCommon.dialogsManager.createWidget(
      "lightbox",
      {
        id: "full-elementor-ai",
        headerMessage: false,
        message: "",
        hide: {
          auto: false,
          onClick: false,
          onOutsideClick: false,
          onOutsideContextMenu: false,
          onBackgroundClick: true,
          onEscKeyPress: false,
        },
        className: "elementor-templates-modal",
        closeButton: true,
        closeButtonOptions: {
          iconClass: "eicon-close",
        },
        draggable: false,
        onShow: function () {
          const container = window.FullIaModal.getElements("content");
          container.get(0).innerHTML = $("#full-ai-prompt").html();

          updateIaTemplates();
        },
        onHide: function () {
          const container = window.FullIaModal.getElements("content");
          container.get(0).innerHTML = "";
        },
      }
    );

    window.FullIaModal.getElements("message").append(
      window.FullIaModal.addElement("content")
    );
  };

  const updateIaTemplates = () => {
    if (!IA_TEMPLATES.length && !IA_TEMPLATES_FETCH) {
      fetch(FULL.dashboard_url + "ai/templates")
        .then((response) => response.json())
        .then((response) => {
          IA_TEMPLATES = response;
          IA_TEMPLATES_FETCH = true;
          updateIaTemplates();
        });

      return;
    }

    if (!IA_TEMPLATES.length && IA_TEMPLATES_FETCH) {
      return;
    }

    for (const template of IA_TEMPLATES) {
      let html = $("#full-ai-prompt-template").html();

      html = html.replace("{title}", template.title);
      html = html.replace("{content}", template.content);
      html = html.replace("{json}", JSON.stringify(template));

      $("#prompt-templates").append(html);
    }
  };

  const toggleLoader = () => {
    if ($(ELEMENTS.loader).is(":visible")) {
      $(ELEMENTS.loader).hide();
      $(ELEMENTS.promptContainer).removeClass("loading");
    } else {
      $(ELEMENTS.loader).show();
      $(ELEMENTS.promptContainer).addClass("loading");
    }
  };

  const resetPrompt = () => {
    TW_LETTER_INDEX = 0;
    TW_SOURCE = [];

    $(ELEMENTS.responseContainer).hide();
    $(ELEMENTS.responseContent).html("");
  };

  const showPromptMessage = (message, type) => {
    $(ELEMENTS.alert)
      .removeClass("success, error")
      .addClass(type)
      .text(message)
      .show();

    setTimeout(() => $(ELEMENTS.alert).fadeOut(), 3500);
  };

  const typeWriter = (phrase = 0) => {
    if (typeof TW_SOURCE[phrase] === "undefined") {
      return;
    }

    const line = TW_SOURCE[phrase];

    if (0 === TW_LETTER_INDEX) {
      $(ELEMENTS.responseContent).append('<p class="line-' + phrase + '"></p>');
    }

    const $target = $(ELEMENTS.responseContent).find(".line-" + phrase);

    if (TW_LETTER_INDEX < line.length) {
      $target.text($target.text() + line.charAt(TW_LETTER_INDEX));
      TW_LETTER_INDEX++;
    } else {
      TW_LETTER_INDEX = 0;
      phrase++;
    }

    TW_TIMEOUT = setTimeout(() => typeWriter(phrase), randomSpeed());
  };

  const getGeneratedContentInPlainText = () => {
    let content = getGeneratedContent();
    content = content.replace(/<\/p>/gi, "\n");
    content = content.replace(/<[^>]+>/gi, "");

    return content;
  };

  const requestContentGeneration = () => {
    const endpoint = "full-customer/elementor/ai";

    return fetch(FULL.rest_url + endpoint, {
      method: "POST",
      credentials: "same-origin",
      headers: {
        "Content-Type": "application/json",
        "X-WP-Nonce": FULL.auth,
      },
      body: JSON.stringify({
        prompt: getPromptedMessage(),
        template: IA_MODE?.key ?? "free",
      }),
    }).then((response) => {
      return response.json();
    });
  };

  const updateUsageQuota = (quota) => {
    const usage = (quota.used / quota.granted) * 100;

    $('.ai-usage [data-quota="used"]').text(quota.used);
    $('.ai-usage [data-quota="granted"]').text(quota.granted);
    $(".ai-usage .progress").css("width", usage + "%");
  };

  const createAiTrigger = () => {
    if (!FULL.enabled_services.includes("full-ai")) {
      return;
    }

    if (CURRENT_PANEL.$el.find(".full-ai-trigger").length) {
      return;
    }

    const $title = CURRENT_PANEL.$el.find(".e-ai-button").first();

    $title.after(
      "<span class='full-ai-trigger'><img src='" +
        FULL.ai_icon +
        "' alt='FULL.'></span>"
    );
  };

  const randomSpeed = () => Math.floor(Math.random() * 50);

  const getPromptedMessage = () => $(ELEMENTS.input).val().trim();

  const getGeneratedContent = () => $(ELEMENTS.responseContent).html().trim();

  elementor.hooks.addAction(
    "panel/open_editor/widget",
    function (panel, model, view, event) {
      if (!validWidgets.includes(model.get("widgetType"))) {
        return;
      }

      CURRENT_WIDGET = model;
      CURRENT_PANEL = panel;

      panel.$el.on("DOMSubtreeModified", function () {
        setTimeout(createAiTrigger, 10);
      });

      panel.$el.on("click", ".full-ai-trigger", function () {
        window.FullIaModal.show();
      });
    }
  );

  $(document).on("keyup", ELEMENTS.input, function (e) {
    if (!getPromptedMessage()) {
      $(ELEMENTS.generateTrigger).removeClass("active");
      return;
    }

    $(ELEMENTS.generateTrigger).addClass("active");
  });

  $(document).on("click", ELEMENTS.generateTrigger, function (e) {
    e.preventDefault();

    if (!getPromptedMessage()) {
      showPromptMessage(
        "Por favor, escreva um detalhamento do seu conteúdo",
        "error"
      );
      return;
    }

    toggleLoader();
    resetPrompt();
    $(ELEMENTS.templatesContainer).hide();

    if (TW_TIMEOUT) {
      clearTimeout(TW_TIMEOUT);
      TW_TIMEOUT = null;
    }

    if (!IA_MODE) {
      $(ELEMENTS.iaModeLegend).find("span").text("Texto livre");
      $(ELEMENTS.iaModeLegend).show();
    }

    requestContentGeneration().then((response) => {
      toggleLoader();

      if (response.error) {
        showPromptMessage(response.error, "error");
        return;
      }

      TW_SOURCE = response.content;

      $(ELEMENTS.templatesContainer).hide();
      $(ELEMENTS.responseContainer).show();

      updateUsageQuota(response.quota);

      typeWriter();
    });
  });

  $(document).on("click", ELEMENTS.copyTrigger, function (e) {
    e.preventDefault();

    const content = getGeneratedContentInPlainText();
    if (!content) {
      showPromptMessage("Nenhum conteúdo gerado para copiar", "error");
      return;
    }

    navigator.clipboard.writeText(content);
    showPromptMessage("Texto copiado para a área de transferência", "success");
  });

  $(document).on("click", ELEMENTS.redoTrigger, function (e) {
    e.preventDefault();

    if (!getPromptedMessage()) {
      showErrorMessage(
        "Por favor, informe o detalhamento do que você deseja gerar"
      );
      return;
    }

    $(ELEMENTS.generateTrigger).trigger("click");
  });

  $(document).on("click", ELEMENTS.insertTrigger, function (e) {
    e.preventDefault();

    const content = getGeneratedContent();
    if (!content) {
      showPromptMessage("Nenhum conteúdo gerado para inserir", "error");
      return;
    }

    const $textarea = CURRENT_PANEL.$el.find("textarea").first();
    const editor =
      $textarea && tinymce ? tinymce.get($textarea.attr("id")) : null;

    if ("text-editor" === CURRENT_WIDGET?.get("widgetType") && editor) {
      editor.setContent(content);
      editor.fire("change");
    } else {
      $textarea.val(getGeneratedContentInPlainText()).trigger("input");
    }

    showPromptMessage("Conteúdo inserido com sucesso no Elementor!", "success");
  });

  $(document).on("click", ELEMENTS.promptTemplate, function (e) {
    e.preventDefault();

    const template = $(this).data("template");

    IA_MODE = template;

    $(ELEMENTS.templatesContainer).hide();
    $(ELEMENTS.iaModeLegend).find("span").text(template.title);
    $(ELEMENTS.iaModeLegend).show();
    $(ELEMENTS.input).val("").attr("placeholder", template.value);
  });

  $(document).on("click", ELEMENTS.iaModeChange, function (e) {
    e.preventDefault();

    IA_MODE = null;

    if (TW_TIMEOUT) {
      TW_TIMEOUT = null;
    }

    $(ELEMENTS.templatesContainer).show();
    $(ELEMENTS.iaModeLegend).hide();
    $(ELEMENTS.input).val("");

    resetPrompt();
  });

  initFullIaModal();
})(jQuery);
