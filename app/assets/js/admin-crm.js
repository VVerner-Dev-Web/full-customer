jQuery(function ($) {
  let FORM_STAGES = fullCrm.stages ?? [];
  let KANBAN_ITEMS = [];

  const $table = $("#pipeline-editor");
  const template = $("#stage-template").html();
  const $pipeline = $("#pipeline-kanban");

  const formId = () => $("#formId").val();
  const hash = () => (Math.random() + 1).toString(36).substring(5);

  const generatePipelineEditorTable = () => {
    $table.find("tbody").empty();

    if (!FORM_STAGES[formId()]) {
      return;
    }

    Object.keys(FORM_STAGES[formId()]).forEach((key) => {
      insertStageEditorRow(key);
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
      const $funnel = $("#funnel-container");

      $funnel.empty();

      Object.keys(values).forEach((key) => {
        $cards.filter(`[data-value="${key}"]`).text(values[key]);
      });

      let count = 0;
      const segmentSize = 70 / Object.keys(chart).length;
      const stages = FORM_STAGES[formId()];

      Object.keys(chart).forEach((key) => {
        const $segment = $($("#funnel-segment").html());

        $segment.find(".funnel-segment-title").text(stages[key].name);
        $segment
          .find(".funnel-segment-value")
          .html(` &bullet; ${chart[key]} leads`);
        $segment.css("max-width", `${100 - count * segmentSize}%`);

        $funnel.append($segment);

        count++;
      });
    });
  };

  const generatePipelineKanban = () => {
    $pipeline.empty();
    $pipeline.addClass("loading");

    const kanbanColumnTemplate = $("#kanban-column-template").html();

    if (!FORM_STAGES[formId()]) {
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

        const $col = $(kanbanColumnTemplate).clone();
        $col.addClass("status-" + stage.status);
        $col.find(".kanban-column-title").text(stage.name);
        $col.data("stage", key);

        $pipeline.append($col);

        fillKanbamItems($col);
      });

      startKanban();
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
    const cardTemplate = $("#kanban-card-template").html();
    const stage = $col.data("stage");

    if (typeof KANBAN_ITEMS[stage] === "undefined") {
      return;
    }

    for (const item of KANBAN_ITEMS[stage]) {
      const $card = $(cardTemplate).clone();
      $card.attr("href", fullCrm.leadBaseUrl + item.id);
      $card.data("id", item.id);

      $card.find(".kanban-item-main-key").append(item.main.key);
      $card.find(".kanban-item-main-value").append(item.main.value);

      $col.find(".kanban-column-items").append($card);
    }
  };

  $("#formId").on("change", function () {
    if (!$(this).val()) {
      $("#crm-view-nav, .crm-view").hide();
      return;
    }

    generatePipelineKanban();
    generatePipelineEditorTable();
    generatePipelineAnalytics();

    $("#crm-view-nav").show();
    $('#crm-view-nav a[href="#kanban"]').trigger("click");
  });

  $table.on("click", ".up-stage", function (e) {
    const $tr = $(this).closest("tr");
    const $prevTr = $tr.prev();
    $tr.insertBefore($prevTr);
  });

  $table.on("click", ".remove-stage", function (e) {
    $(this).closest("tr").remove();
  });

  $table.on("click", ".down-stage", function (e) {
    const $tr = $(this).closest("tr");
    const $nextTr = $tr.next();
    $tr.insertAfter($nextTr);
  });

  const insertStageEditorRow = (key = null) => {
    key = key ?? hash();

    const $template = $(template).clone();
    const _stages = FORM_STAGES[formId()] ?? {};

    $template
      .find("input")
      .val(_stages[key] ? _stages[key].name : "")
      .attr("name", "stage[" + key + "][name]");

    $template
      .find("select")
      .val(_stages[key] ? _stages[key].status : "")
      .attr("name", "stage[" + key + "][status]");

    $table.find("tbody").append($template);
  };

  $table.on("click", ".add-stage", function (e) {
    insertStageEditorRow();
  });

  $(window).on("full/form-received/full-crm", function (e, response) {
    FORM_STAGES = response.data.stages ?? [];

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

  $("#crm-view-nav a").on("click", function (e) {
    e.preventDefault();

    const $target = $($(this).attr("href"));

    $("#crm-view-nav a").not(this).removeClass("active");
    $(this).addClass("active");

    $(".crm-view").hide();
    $target.show();
  });
});
