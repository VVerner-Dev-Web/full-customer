jQuery(function ($) {
  let JOURNEYS_LIST = {};
  let JOURNEYS_STATS = JOURNEYS_LIST;

  const $tabLinks = $("#analytics-view-nav a");
  const $periodInput = $("#dataPeriod");
  const $stageEditor = $("#pipeline-editor");

  $tabLinks.on("click", function (e) {
    e.preventDefault();

    const $target = $($(this).attr("href"));

    $tabLinks.not(this).removeClass("active");
    $(this).addClass("active");

    $(".analytics-view").hide();
    $target.show();
  });

  $tabLinks.first().trigger("click");

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
    console.log(data);

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
      const transition =
        previous === null ? 100 : (item.value / previous) * 100;

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

  const updateView = () => {
    $.post(ajaxurl, $("#data-period-form").serialize(), function (response) {
      const { totals, tables, chartData, journeysList, journeyStats } =
        response;

      updateTotalsPageViews(totals);
      updatePageViews(tables);
      updateChartPageViews(chartData);
      updateJourneyList(journeysList);
      updateJourneyChart(journeyStats);
    });
  };

  $(".full-modal-close").on("click", function () {
    $("#journeyId").val("");
    $("#journeyName").val("");
    $stageEditor.find("tbody").empty();
  });

  $(".add-stage").on("click", function (e) {
    const $template = $($("#journey-stage-row").html());
    $stageEditor.find("tbody").append($template);
  });

  $("#chartJourney").on("change", updateJourneyChart);

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
            console.log(journeysList);
            updateJourneyList(journeysList.data);
          }
        );
      }
    });
  });

  $(document).on("click", ".journey-view", function (e) {
    e.preventDefault();

    const $tr = $(this).closest("tr");
    const $template = $($("#existing-journey-row").html());
    $template.data("journey", $tr.data("journey"));
  });

  $(window).on("full/form-received/full-analytics-settings", updateView);
  $(window).on("full/form-received/full-analytics-journey", function () {
    $(".full-modal-close").trigger("click");
    updateView();
  });

  picker.on("selected", updateView);
  updateView();
});
