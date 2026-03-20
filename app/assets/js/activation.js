jQuery(document).ready(function ($) {
  const $grid = $("#fc-plugins-grid");
  const $dialog = document.getElementById("fc-activation-dialog");
  const $startBtn = $("#start-activation");
  const $progressDiv = $("#activation-progress");
  const config = FULL_ACTIVATION;

  let selectedPlugin = null;

  function loadRepository() {
    $grid.html("<p>Carregando catálogo...</p>");
    $.getJSON(config.repository, function (response) {
      if (response.success) renderGrid(response.data);
    });
  }

  function renderGrid(plugins) {
    $grid.empty();

    plugins.forEach((plugin) => {
      let statusLabel = "Não instalado";
      let statusClass = "status-missing";

      if (plugin.exists) {
        statusLabel = plugin.is_active ? "Ativo no WP" : "Inativo no WP";
        statusClass = plugin.is_active ? "status-active" : "status-inactive";
      }

      let button = `<button class="button btn-install"  data-index="${plugin.plugin}"  data-name="${plugin.name}">Solicitar ativação</button>`;
      let licenseBadge = "";

      if (!plugin.balance.available) {
        button = `<a class="button btn-manage" href="https://full.services" target="_blank" rel="noopener noreferrer">Comprar licença</a>`;
      }

      if (plugin.activation.id) {
        const licenseClass =
          plugin.activation.status === "success" ? "license-yes" : "license-no";

        button = `<a class="button btn-manage" href="https://painel.full.services" target="_blank" rel="noopener noreferrer">Gerenciar</a>`;
        licenseBadge = `<span class="fc-badge ${licenseClass}">${plugin.activation.status_label}</span>`;
      }

      const card = `
        <div class="fc-plugin-card">
            <img src="${plugin.image_url}" alt="${plugin.name}">
            <h3>${plugin.name}</h3>
            
            <div class="fc-card-badges">
                <span class="fc-badge ${statusClass}">${statusLabel}</span>
                ${licenseBadge}
            </div>

            ${button}
        </div>`;

      $grid.append(card);
    });
  }

  $grid.on("click", ".btn-install", function () {
    selectedPlugin = {
      index: $(this).data("index"),
      name: $(this).data("name"),
    };

    $("[data-plugin-name]").text(selectedPlugin.name);

    $progressDiv.hide().empty();
    $startBtn.prop("disabled", false).text("Ativar");

    $dialog.showModal();
  });

  $(".dialog-close").on("click", function () {
    $dialog.close();
  });

  $startBtn.on("click", function () {
    if (!selectedPlugin) return;

    const $btn = $(this);
    $btn.prop("disabled", true).text("Processando...");
    $progressDiv.show().html("> Iniciando conexão com servidor FULL...");

    const progressInterval = setInterval(() => {
      $.post(
        config.installPluginProgress,
        { plugin: selectedPlugin.index },
        function (res) {
          if (res.success && res.data !== "> ") $progressDiv.html(res.data);
        },
      );
    }, 2000);

    $.ajax({
      url: config.installPlugin,
      method: "POST",
      data: { plugin: selectedPlugin.index },
      success: function (response) {
        clearInterval(progressInterval);
        if (response.success) {
          $progressDiv.append(
            '<br><strong style="color: #eacd2f;">> Processo finalizado com sucesso!</strong>',
          );
          $btn.text("Concluído");
          loadRepository();

          setTimeout(() => {
            $dialog.close();
          }, 3000);
        } else {
          $progressDiv.append(
            '<br><strong style="color: #ef4444;">> Erro: ' +
              response.data +
              "</strong>",
          );
          $btn.prop("disabled", false).text("Tentar novamente");
        }
      },
    });
  });

  loadRepository();
});
