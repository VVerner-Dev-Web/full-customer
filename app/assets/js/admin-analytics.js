jQuery(function ($) {
  const $tabLinks = $("#analytics-view-nav a");
  const $periodInput = $("#dataPeriod");

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

  const chart = new Chart(document.getElementById("dashboard-chart"), {
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

  const numberFormat = (number, decimals) =>
    number.toLocaleString("pt-BR", {
      maximumFractionDigits: decimals,
    });

  const updateView = () => {
    $.post(ajaxurl, $("#data-period-form").serialize(), function (response) {
      const { totals, tables, chartData } = response;

      $(".totals-sessions").text(numberFormat(totals.sessions, 0));
      $(".totals-views").text(numberFormat(totals.views, 0));
      $(".totals-average").text(numberFormat(totals.average, 2));
      $(".top-page").text(tables.pages[0]?.item ?? "Sem dados");

      chart.data = chartData;
      chart.update();

      $("#table-pages, #table-query-strings").each(function () {
        const $el = $(this);
        const data = tables[$el.data("table")];

        $el.empty();

        console.log(data);

        for (const row of data) {
          $el.append(
            `<tr><th><strong>${row.item}</strong></th><td>${row.entries}</td></tr>`
          );
        }
      });
    });
  };

  picker.on("selected", updateView);
  updateView();
});
