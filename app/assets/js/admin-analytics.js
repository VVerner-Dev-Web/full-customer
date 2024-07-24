jQuery(function ($) {
  let JOURNEYS_LIST = {};
  let JOURNEYS_STATS = JOURNEYS_LIST;
  let CONVERSIONS_LIST = [];

  const CONVERSION_TYPES = {
    "element:click": "Clique em elemento",
    "element:submit": "Envio de formulário",
    "page:view": "Acesso a página",
  };

  const $periodInput = $("#dataPeriod");
  const $stageEditor = $("#pipeline-editor");

  const picker = new Litepicker({
    autoRefresh: true,
    element: $periodInput[0],
    format: "DD/MM/YYYY",
    lang: "pt-BR",
    maxDate: new Date(),
    singleMode: false,
    showTooltip: false,
    numberOfColumns: 1,
    numberOfMonths: 1,
  });

  const dashboardChart = new Chart(document.getElementById("dashboard-chart"), {
    type: "bar",
    data: {
      labels: [],
      datasets: [],
    },
    options: {
      responsive: true,
      interaction: {
        mode: "index",
        intersect: false,
      },
      stacked: false,
      plugins: {
        legend: {
          position: "top",
        },
        title: {
          display: false,
        },
      },
      scales: {
        y: {
          beginAtZero: false,
          type: "linear",
          display: true,
          position: "left",
        },
        y1: {
          beginAtZero: true,
          type: "linear",
          display: true,
          position: "right",
          grid: {
            drawOnChartArea: false, // only want the grid lines for one axis to show up
          },
        },
      },
    },
  });

  const journeyChart = new Chart(
    document.getElementById("current-journey-chart"),
    {
      type: "bar",
      data: {
        labels: [],
        datasets: [
          {
            label: "Visitantes",
            data: [],
            borderColor: "rgba(0,201,167,1)",
            backgroundColor: "rgba(0,201,167,.5)",
          },
        ],
      },
      options: {
        responsive: true,
        interaction: {
          mode: "index",
          intersect: false,
        },
        stacked: false,
        plugins: {
          legend: {
            position: "top",
          },
          title: {
            display: false,
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            type: "linear",
            display: true,
            position: "left",
          },
        },
      },
    }
  );

  const numberFormat = (number, decimals) =>
    number.toLocaleString("pt-BR", {
      maximumFractionDigits: decimals,
    });

  const updateTotalsPageViews = (totals) => {
    $(".totals-sessions").text(numberFormat(totals.sessions, 0));
    $(".totals-views").text(numberFormat(totals.views, 0));
    $(".totals-average").text(numberFormat(totals.average, 2));
  };

  const updatePageViews = (tables) => {
    $(".top-page").text(tables.pages[0]?.item ?? "Sem dados");
    $("#table-pages").each(function () {
      const $el = $(this);
      const data = tables[$el.data("table")];

      $el.empty();
      for (const row of data) {
        $el.append(
          `<tr><th><strong>${row.item}</strong></th><td>${row.entries}</td></tr>`
        );
      }
    });
  };

  const updateChartPageViews = (chartData) => {
    dashboardChart.data = chartData;
    dashboardChart.update();
  };

  const updateJourneyChart = (stats) => {
    JOURNEYS_STATS = typeof stats === "object" ? stats : JOURNEYS_STATS;
    const data = JOURNEYS_STATS[$("#chartJourney").val()] ?? [];

    journeyChart.data.labels = data.map((item) => item.name);
    journeyChart.data.datasets[0].data = data.map((item) => item.value);
    journeyChart.update();

    $("#journey-rate").empty();

    let funnelEntries = data[0]?.value;
    let previous = null;

    if (!funnelEntries) {
      return;
    }

    for (const item of data) {
      const fromTotal = (item.value / funnelEntries) * 100;
      let transition = previous === null ? 100 : (item.value / previous) * 100;

      if (previous === 0) {
        transition = 0;
      }

      $("#journey-rate").append(
        `<tr><td><strong>${item.name}</strong></td><td>${numberFormat(
          transition,
          2
        )}%</td><td>${numberFormat(fromTotal, 2)}%</td></tr>`
      );
      previous = item.value;
    }
  };

  const updateJourneyList = (journeysList = null) => {
    JOURNEYS_LIST =
      typeof journeysList === "object" ? journeysList : JOURNEYS_LIST;

    $(".show-for-journeys").hide();
    $(".hide-for-journeys").show();

    $("#current-journeys tbody, #chartJourney").empty();

    Object.keys(JOURNEYS_LIST).forEach((key) => {
      $(".show-for-journeys").show();
      $(".hide-for-journeys").hide();

      const journey = journeysList[key];
      const $row = $($("#existing-journey-row").html());

      $row.find(".journey-name").text(journey.name);
      $row.find(".journey-stages").text(journey.stages.length);
      $row.data("journey", journey);

      $("#current-journeys tbody").append($row);
      $("#chartJourney").append(
        '<option value="' + journey.id + '">' + journey.name + "</option>"
      );
    });

    $("#chartJourney").trigger("change");
  };

  const updateConversionList = (conversionsList = null) => {
    CONVERSIONS_LIST =
      typeof conversionsList === "object" ? conversionsList : CONVERSIONS_LIST;

    $("#current-conversions tbody").empty();

    CONVERSIONS_LIST.forEach((conv) => {
      const $row = $($("#existing-conversion-row").html());

      $row.find(".conversion-name").text(conv.name);
      $row.find(".conversion-type").text(CONVERSION_TYPES[conv.type]);
      $row.find(".conversion-element").text(conv.element);
      $row.find(".conversion-global-rate").text(conv.performance.conversion);

      for (const key of Object.keys(conv.performance)) {
        if (key !== "conversion") {
          const $period = $row.find('[data-period="' + key + '"]');
          $period.find(".current").text(conv.performance[key].current);
          $period.find(".change").text(conv.performance[key].change);
          $period.find(".change").addClass(conv.performance[key].trending);
        }
      }

      $row.data("conversion", conv);

      $("#current-conversions tbody").append($row);
    });
  };

  const updateView = () => {
    $.post(ajaxurl, $("#data-period-form").serialize(), function (response) {
      updateTotalsPageViews(response.totals);
      updatePageViews(response.tables);
      updateChartPageViews(response.chartData);
      updateJourneyList(response.journeysList);
      updateJourneyChart(response.journeyStats);
      updateConversionList(response.conversionsList);
    });
  };

  $(document).on("modal-closed/modal-conversion-editor", function () {
    $(
      "#conversionName, #conversionType, #conversionElement, #conversionId"
    ).val("");

    $(".conversion-tutorial").hide();
  });

  $(document).on("modal-closed/modal-journey-editor", function () {});

  $(".full-modal-close").on("click", function () {
    $("#journeyId, #journeyName").val("");
    $stageEditor.find("tbody").empty();
  });

  $(".add-stage").on("click", function (e) {
    const $template = $($("#journey-stage-row").html());
    $stageEditor.find("tbody").append($template);
  });

  $("#chartJourney").on("change", () => updateJourneyChart());

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

  $(document).on("click", ".journey-edit", function (e) {
    e.preventDefault();
    const data = $(this).closest("tr").data("journey");

    $("#journeyName").val(data.name);
    $("#journeyId").val(data.id);

    for (const stage of data.stages) {
      const $template = $($("#journey-stage-row").html());
      $template.find("input").val(stage);
      $stageEditor.find("tbody").append($template);
    }

    $('[data-modal="#modal-journey-editor"]').trigger("click");
  });

  $(document).on("click", ".journey-delete", function (e) {
    e.preventDefault();

    Swal.fire({
      icon: "warning",
      text: "Tem certeza que deseja excluir esta jornada?",
      showConfirmButton: false,
      showDenyButton: true,
      showCancelButton: true,
      cancelButtonText: "Voltar",
      denyButtonText: "Excluir",
    }).then((result) => {
      if (result.isDenied) {
        const $tr = $(this).closest("tr");

        $.post(
          ajaxurl,
          {
            journeyId: $tr.data("journey").id,
            action: "full/analytics/journey/delete",
          },
          function (journeysList) {
            updateJourneyList(journeysList.data);
          }
        );
      }
    });
  });

  $(document).on("change", "#conversionType", function (e) {
    const tutorial = $(this).val().split(":").pop();

    $(".conversion-tutorial.for-" + tutorial).show();
  });

  $(document).on("click", ".journey-view", function (e) {
    e.preventDefault();

    const $tr = $(this).closest("tr");
    const $template = $($("#existing-journey-row").html());
    $template.data("journey", $tr.data("journey"));
  });

  $(document).on("click", ".conversion-edit", function (e) {
    e.preventDefault();
    const data = $(this).closest("tr").data("conversion");

    $(".conversion-tutorial").hide();

    $("#conversionName").val(data.name);
    $("#conversionType").val(data.type).trigger("change");
    $("#conversionElement").val(data.element);
    $("#conversionId").val(data.id);

    $('[data-modal="#modal-conversion-editor"]').trigger("click");
  });

  $(document).on("click", ".conversion-delete", function (e) {
    e.preventDefault();

    Swal.fire({
      icon: "warning",
      text: "Tem certeza que deseja excluir esta conversão?",
      showConfirmButton: false,
      showDenyButton: true,
      showCancelButton: true,
      cancelButtonText: "Voltar",
      denyButtonText: "Excluir",
    }).then((result) => {
      if (result.isDenied) {
        const $tr = $(this).closest("tr");

        console.log($tr.data("conversion"));

        $.post(
          ajaxurl,
          {
            id: $tr.data("conversion").id,
            action: "full/analytics/conversion/delete",
          },
          function (list) {
            updateConversionList(list.data);
          }
        );
      }
    });
  });

  $(window).on("full/form-received/full-analytics-settings", updateView);
  $(window).on("full/form-received/full-analytics-journey", function () {
    $(".full-modal-close").trigger("click");
    updateView();
  });

  picker.on("selected", updateView);
  updateView();
});
