jQuery(function ($) {
  let FORM_STAGES = fullCrm.stages ?? [];
  let CARD_FRAGMENTS = fullCrm.fragments ?? [];
  let KANBAN_ITEMS = [];

  const $cardEditor = $("#card-editor");
  const $stageEditor = $("#pipeline-editor");
  const $pipeline = $("#pipeline-kanban");
  const $formId = $("#formId");
  const $leadSearch = $("#lead-search");
  const $reloadKanban = $(".reload-kanban");
  const $funnelContainer = $("#funnel-container");
  const $crmViewNavLinks = $("#crm-view-nav a");

  const TEMPLATES = {
    kanbanColumn: $("#kanban-column-template").html(),
    kanbanCard: $("#kanban-card-template").html(),
    cardFragment: $("#card-fragment-template").html(),
    stage: $("#stage-template").html(),
    cardFragmentEditor: $("#card-fragment-editor-template").html(),
    funnelSegment: $("#funnel-segment").html(),
  };

  const formId = () => $formId.val();
  const hash = () => (Math.random() + 1).toString(36).substring(5);
  const toggleLoader = () => $reloadKanban.toggleClass("loading");

  const slugfy = (str) => {
    str = str.replace(/^\s+|\s+$/g, "");
    str = str.toLowerCase();

    const from = "àáäâèéëêìíïîòóöôùúüûñçěščřžýúůďťň·/_,:;";
    const to = "aaaaeeeeiiiioooouuuuncescrzyuudtn------";

    for (let i = 0, l = from.length; i < l; i++) {
      str = str.replace(new RegExp(from.charAt(i), "g"), to.charAt(i));
    }

    return str
      .replace(".", "-") // replace a dot by a dash
      .replace(/[^a-z0-9 -]/g, "") // remove invalid chars
      .replace(/\s+/g, "-") // collapse whitespace and replace by a dash
      .replace(/-+/g, "-") // collapse dashes
      .replace(/\//g, ""); // collapse all forward-slashes
  };

  const generatePipelineEditorTable = () => {
    $stageEditor.find("tbody").empty();

    if (!FORM_STAGES[formId()]) {
      return;
    }

    Object.keys(FORM_STAGES[formId()]).forEach((key) => {
      insertStageEditorRow(key);
    });
  };

  const generatePipelineCardEditor = () => {
    $cardEditor.find("tbody").empty();

    const data = {
      formId: formId(),
      action: "full/widget/crm/form/get-fields",
    };

    const current = CARD_FRAGMENTS[formId()] ?? [];

    $.post(ajaxurl, data, function (response) {
      for (const key in response) {
        const $template = $(TEMPLATES.cardFragmentEditor).clone();

        $template.find("label").attr("for", "input-" + slugfy(key));
        $template.find(".fragment-name").text(response[key]);
        $template
          .find("input")
          .val(key)
          .attr("id", "input-" + slugfy(key))
          .attr("checked", current.includes(key))
          .prop("checked", current.includes(key));
        $cardEditor.find("tbody").append($template);
      }
    });
  };

  const generatePipelineAnalytics = () => {
    const $cards = $(".crm-card-value");
    $cards.text("0");

    const data = {
      formId: formId(),
      action: "full/widget/crm/form/get-analytics",
    };

    $.post(ajaxurl, data, function (response) {
      const { chart, values } = response;

      $funnelContainer.empty();

      Object.keys(values).forEach((key) => {
        $cards.filter(`[data-value="${key}"]`).text(values[key]);
      });

      let count = 0;
      const segmentSize = 70 / Object.keys(chart).length;
      const stages = FORM_STAGES[formId()];

      Object.keys(chart).forEach((key) => {
        const $segment = $(TEMPLATES.funnelSegment);

        $segment.find(".funnel-segment-title").text(stages[key].name);
        $segment
          .find(".funnel-segment-value")
          .html(` &bullet; ${chart[key]} leads`);
        $segment.css("max-width", `${100 - count * segmentSize}%`);

        $funnelContainer.append($segment);

        count++;
      });
    });
  };

  const generatePipelineKanban = () => {
    toggleLoader();

    $pipeline.empty();
    $pipeline.addClass("loading");

    if (!FORM_STAGES[formId()]) {
      toggleLoader();
      $pipeline.text(
        'Você precisa definir os estágio do funil na aba "Editor" antes de começar'
      );
      $pipeline.removeClass("loading");
      return;
    }

    const data = {
      formId: formId(),
      action: "full/widget/crm/form/get-leads",
    };

    $.post(ajaxurl, data, function (items) {
      $pipeline.removeClass("loading");

      KANBAN_ITEMS = items ?? [];

      Object.keys(FORM_STAGES[formId()]).forEach((key) => {
        const stage = FORM_STAGES[formId()][key];

        const $col = $(TEMPLATES.kanbanColumn).clone();
        $col.addClass("status-" + stage.status);
        $col.find(".kanban-column-title").text(stage.name);
        $col.data("stage", key);

        $pipeline.append($col);

        fillKanbamItems($col);
      });

      startKanban();

      toggleLoader();
    });
  };

  const startKanban = () => {
    $(".kanban-column .kanban-column-items")
      .sortable({
        connectWith: ".kanban-column .kanban-column-items",
        placeholder: "ui-state-highlight",
        stop: function (event, ui) {
          $.post(ajaxurl, {
            id: ui.item.data("id"),
            stage: ui.item.parents(".kanban-column").data("stage"),
            action: "full/widget/crm/lead/update",
          });
        },
      })
      .disableSelection();
  };

  const fillKanbamItems = ($col) => {
    const stage = $col.data("stage");

    if (typeof KANBAN_ITEMS[stage] === "undefined") {
      return;
    }

    const fragments = CARD_FRAGMENTS[formId()] ?? [];

    for (const item of KANBAN_ITEMS[stage]) {
      const $card = $(TEMPLATES.kanbanCard).clone();
      $card.attr("href", fullCrm.leadBaseUrl + item.id);
      $card.data("id", item.id);

      for (const value of item.values) {
        if (!fragments.includes(value.key)) {
          continue;
        }

        const $fragment = $(TEMPLATES.cardFragment).clone();
        $fragment.find(".kanban-item-key").text(item.labels[value.key] ?? "");
        $fragment.find(".kanban-item-value").text(value.value);

        $card.find(".kanban-item-fragments").append($fragment);
      }
      $col.find(".kanban-column-items").append($card);
    }
  };

  $("#formId").on("change", function () {
    $leadSearch.val("");

    if (!$(this).val()) {
      $(".lead-search").removeClass("enabled");
      $("#crm-view-nav, .crm-view").hide();
      return;
    }

    generatePipelineKanban();
    generatePipelineEditorTable();
    generatePipelineCardEditor();
    generatePipelineAnalytics();

    $(".lead-search").addClass("enabled");
    $("#crm-view-nav").show();
    $('#crm-view-nav a[href="#kanban"]').trigger("click");
  });

  $leadSearch.on("keyup", function () {
    $(".kanban-item").show();
    const term = slugfy($(this).val());

    $(".kanban-item").each(function () {
      const $card = $(this);

      let found = false;

      $card.find(".kanban-item-value").each(function () {
        const cardTerm = slugfy($(this).text());
        found = found || cardTerm.indexOf(term) !== -1;
      });

      $card.toggle(found);
    });
  });

  $reloadKanban.on("click", function () {
    generatePipelineKanban();
    generatePipelineEditorTable();
    generatePipelineCardEditor();
    generatePipelineAnalytics();
  });

  $stageEditor.on("click", ".up-stage", function (e) {
    const $tr = $(this).closest("tr");
    const $prevTr = $tr.prev();
    $tr.insertBefore($prevTr);
  });

  $stageEditor.on("click", ".remove-stage", function (e) {
    $(this).closest("tr").remove();
  });

  $stageEditor.on("click", ".down-stage", function (e) {
    const $tr = $(this).closest("tr");
    const $nextTr = $tr.next();
    $tr.insertAfter($nextTr);
  });

  const insertStageEditorRow = (key = null) => {
    key = key ?? hash();

    const $template = $(TEMPLATES.stage).clone();
    const _stages = FORM_STAGES[formId()] ?? {};

    $template
      .find("input")
      .val(_stages[key] ? _stages[key].name : "")
      .attr("name", "stage[" + key + "][name]");

    $template
      .find("select")
      .val(_stages[key] ? _stages[key].status : "")
      .attr("name", "stage[" + key + "][status]");

    $stageEditor.find("tbody").append($template);
  };

  $stageEditor.on("click", ".add-stage", function (e) {
    insertStageEditorRow();
  });

  $(window).on("full/form-received/full-crm", function (e, response) {
    FORM_STAGES = response.data.stages ?? [];
    CARD_FRAGMENTS = response.data.fragments ?? [];

    generatePipelineKanban();
  });

  $pipeline.on("click", ".hide-lead, .delete-lead", function (e) {
    e.preventDefault();

    const $card = $(this).parents(".kanban-item");

    const data = {
      id: $card.data("id"),
      action: "full/widget/crm/lead/" + $(this).data("action"),
    };

    $card.remove();

    $.post(ajaxurl, data);
  });

  $crmViewNavLinks.on("click", function (e) {
    e.preventDefault();

    const $target = $($(this).attr("href"));

    $crmViewNavLinks.not(this).removeClass("active");
    $(this).addClass("active");

    $(".crm-view").hide();
    $target.show();
  });
});
