(function ($) {
  "use strict";

  const $registerForm = $("#full-register");
  const $connectForm = $("#full-connect");
  const $navLinks = $("#form-nav .nav-link");

  var failedApplicationPassword = false;

  $connectForm.on("submit", function (e) {
    e.preventDefault();

    focusForm("#" + $connectForm.attr("id"));

    const dashboardEmail = $connectForm.find("#customer-email").val();
    const wpUserPassword = $connectForm.find("#customer-password").val();

    $connectForm.find("button").addClass("loading");

    if (wpUserPassword && failedApplicationPassword) {
      connectSite(dashboardEmail, wpUserPassword, "user_password")
        .then((response) => response.json())
        .then((response) => {
          $connectForm.find("button").removeClass("loading");
          handleSiteConnectionResponse(response);
        });
    } else {
      generateApplicationPassword()
        .then((response) => response.json())
        .then((response) => {
          if (response.code === "application_passwords_disabled") {
            fireAlert(
              "error",
              "As senhas de aplicação estão indisponíveis em seu site. Por favor, informe a senha do seu usuário administrador do WordPress."
            );
            showCustomerPasswordInput();

            $connectForm.find("button").removeClass("loading");
            failedApplicationPassword = true;
            return;
          }

          const { password } = response;

          connectSite(dashboardEmail, password, "application_password")
            .then((response) => response.json())
            .then((response) => {
              $connectForm.find("button").removeClass("loading");
              handleSiteConnectionResponse(response);
            });
        });
    }
  });

  $registerForm.on("submit", function (e) {
    e.preventDefault();

    focusForm("#" + $registerForm.attr("id"));

    const name = $registerForm.find("#register-name").val();
    const email = $registerForm.find("#register-email").val();
    const password = $registerForm.find("#register-password").val();
    const tryConnect = $registerForm
      .find("#register-try_connect")
      .is(":checked");

    $registerForm.find("button").addClass("loading");

    createUser(name, email, password)
      .then((response) => response.json())
      .then((response) => {
        $registerForm.find("button").removeClass("loading");

        if (tryConnect && response.success) {
          fireAlert(
            "success",
            "Cadastro feito com sucesso! Iremos tentar realizar a conexão do seu site."
          ).then(() => {
            $connectForm.find("#customer-email").val(email);
            $connectForm.trigger("submit");
          });
          return;
        }

        if (response.success) {
          fireAlert("success", "Cadastro feito com sucesso!");
          return;
        }

        if (
          response.code === "existing_user_login" ||
          response.code === "existing_user_email"
        ) {
          fireAlert("error", "O e-mail informado já está em uso na FULL.");
          return;
        }

        fireAlert("error", response.message);
      });
  });

  $navLinks.on("click", function (e) {
    e.preventDefault();

    const $clickedItem = $(this);

    $navLinks.removeClass("active");
    $clickedItem.addClass("active");

    const target = $(this).attr("href");

    $registerForm.hide();
    $connectForm.hide();
    $(target).show();
  });

  const focusForm = (formSelector) => {
    $navLinks.filter('[href="' + formSelector + '"]').trigger("click");
  };

  const createUser = (name, email, password) => {
    const endpoint = "register-user";
    const request = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        name: name,
        email: email,
        password: password,
      }),
    };

    return fetch(FULL.dashboard_url + endpoint, request);
  };

  const showCustomerPasswordInput = () => {
    $('label[for="customer-password"]').css("display", "block");
    $("#customer-password").attr("required", true).prop("required", true);
  };

  const generateApplicationPassword = () => {
    const endpoint = "wp/v2/users/me/application-passwords";
    const request = {
      method: "POST",
      credentials: "same-origin",
      headers: {
        "Content-Type": "application/json",
        "X-WP-Nonce": FULL.auth,
      },
      body: JSON.stringify({
        name: "Conexão com painel FULL id:" + Math.ceil(Math.random() * 1000),
      }),
    };

    return fetch(FULL.rest_url + endpoint, request);
  };

  const connectSite = (dashboardEmail, password, password_origin) => {
    const endpoint = "connect-site";
    const request = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        user: FULL.user_login,
        site_url: FULL.site_url,
        password: password,
        password_origin: password_origin,
        email: dashboardEmail,
      }),
    };

    return fetch(FULL.dashboard_url + endpoint, request);
  };

  const handleSiteConnectionResponse = (response) => {
    if (response.success) {
      const endpoint = "full-customer/connect";
      const request = {
        method: "POST",
        credentials: "same-origin",
        headers: {
          "Content-Type": "application/json",
          "X-WP-Nonce": FULL.auth,
        },
        body: JSON.stringify({
          connection_email: response.connection_email,
          dashboard_url: response.dashboard_url,
        }),
      };

      fetch(FULL.rest_url + endpoint, request);

      fireAlert("success", "Site conectado com sucesso!").then(() => {
        location.reload();
      });
    } else if (response.code === "user_not_found") {
      fireAlert(
        "warning",
        "O email que você informou não está cadastrado na FULL."
      );
      return;
    } else if (response.code === "site_already_connected") {
      fireAlert(
        "warning",
        "Este site já foi conectado anteriormente no painel da FULL."
      );
      return;
    } else {
      fireAlert(
        "error",
        "Algo deu errado, tente conectar o site diretamente pelo painel da FULL."
      );
      return;
    }
  };

  const fireAlert = (type, message) => {
    const titles = {
      success: "🎉 Tudo certo",
      error: "📢 Algo deu errado",
      warning: "🧐 Ei",
    };

    return Swal.fire({
      titleText: titles[type],
      text: message,
    });
  };

  // WIDGETS
  // ========================
  if ($(".widgets-grid").length) {
    const $grids = $(".widgets-grid");
    const changed = [];

    $.get(
      FULL.dashboard_url + "widgets",
      { site: FULL.site_url },
      function (response) {
        for (const widget of response) {
          let html = $("#widget-template").html();

          Object.entries(widget).forEach(([key, value]) => {
            html = html.replace(new RegExp("{" + key + "}", "g"), value);
          });

          if (FULL.enabled_services.includes(widget.key)) {
            html = html.replace("{checked}", "checked");
          }

          html = html.replace("{checked}", "");

          const $clone = $(html);

          if ("addon" === widget.tier && !widget.purchased) {
            $clone.find(".status").remove();
          }

          if (widget.required) {
            $clone.find(".status").text("Obrigatório");
            $clone.addClass("widget-required");
          }

          $grids
            .filter(".widgets-" + widget.tier)
            .append($clone.prop("outerHTML"));
        }
      }
    );

    $grids.on("change", "input", function () {
      const key = $(this).val();
      const index = changed.indexOf(key);

      index > -1 ? changed.splice(index, 1) : changed.push(key);
    });

    $("#update-widgets").on("click", function () {
      const count = changed.length;
      if (!count) {
        Swal.fire(
          "Ops",
          "O status de menhuma extensão foi modificado para atualizarmos.",
          "info"
        );
        return;
      }

      const legend = count > 1 ? " extensões" : " extensão";

      Swal.fire({
        titleText: "Quase lá!",
        html: "Tem certeza que deseja alterar o status de " + count + legend,
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: "Sim, continuar",
        cancelButtonText: "Voltar",
        showLoaderOnConfirm: true,
        backdrop: true,
        allowOutsideClick: () => !Swal.isLoading(),
        customClass: {
          container: "full-template-popup full-template-dependencies-popup",
        },
        preConfirm: () => {
          toggleWidgetsStatus(changed);

          return new Promise((resolve, reject) => {
            let index = 0;
            let messages = [
              "Preparando para decolar...",
              "Checando compatibilidades e dependências...",
              "Configurando as extensões...",
              "Conferindo últimos ajustes...",
              "Aperte os cintos, vamos decolar...",
            ];

            let interval = setInterval(() => {
              const message = messages[index];

              if (!message) {
                clearInterval(interval);
                resolve();
              }

              $("#swal2-html-container").text(message);

              index++;
            }, 1000);
          });
        },
      }).then((response) => {
        if (!response.isConfirmed) {
          return;
        }

        location.reload();
      });
    });

    function toggleWidgetsStatus(widgets) {
      const endpoint = "full-customer/toggle-widgets?widgets=" + widgets.join();
      return fetch(FULL.rest_url + endpoint, {
        method: "POST",
        credentials: "same-origin",
        headers: {
          "Content-Type": "application/json",
          "X-WP-Nonce": FULL.auth,
        },
      });
    }
  }

  // WIDGETS SETTINGS FORMS
  // ========================
  $("form.full-widget-form").on("submit", function (e) {
    e.preventDefault();

    const $form = jQuery(this);
    const $btn = $form.find("button");
    $btn.addClass("loading");

    $(window).trigger("full/form-submitted/" + $form.attr("id"));

    jQuery.post(ajaxurl, $form.serialize(), function (response) {
      $btn.removeClass("loading");

      $(window).trigger("full/form-received/" + $form.attr("id"), response);

      fireAlert(
        response?.success ? "success" : "warning",
        response?.success
          ? "Configurações atualizadas com sucesso"
          : "Falha ao atualizar as configurações, tente novamente por favor"
      );

      if (response?.data?.reload) {
        location.reload();
      }
    });
  });

  // TABS
  // ========================
  $(".full-page-content .tabs a").on("click", function (e) {
    e.preventDefault();

    const $el = $(this);
    const $panel = $($el.attr("href"));

    if (!$panel.length) {
      return alert("Em breve");
    }

    $(".full-page-content .tabs a").removeClass("active");
    $(".full-page-content .tabbed-content .full-widget-form").hide();

    $el.addClass("active");
    $panel.slideToggle();
  });

  // ACCESS TOKEN
  // ========================
  $("[data-js='full-generate-temporary-token']").on("click", function (e) {
    e.preventDefault();

    const $btn = $(this);
    $btn.addClass("disabled").text("Gerando...").attr("disabled", true);

    const data = {
      action: "full/generate-temporary-token",
      userId: $(this).data("user"),
    };

    $.post(ajaxurl, data, function ({ data }) {
      $btn.removeClass("disabled").text("Gerar link").attr("disabled", false);

      prompt(
        "URL temporária criada com sucesso! Este token é de uso único e recriado em cada nova solicitação",
        data
      );

      navigator.clipboard.writeText(data);
    });
  });
})(jQuery);
