jQuery(function ($) {
  const $dialog = $("#full-staff-modal");
  const $repositoryContainer = $(".fsm-repository");
  const $responseContainer = $(".fsm-response");

  let buffer = [];

  $(document).on("keydown", function (e) {
    const combo = ["f", "u", "l", "l"];
    if (e.shiftKey) {
      const key = e.key.toLowerCase();

      if (/^[a-z]$/.test(key)) {
        buffer.push(key);

        if (buffer.length > combo.length) {
          buffer.shift();
        }

        if (buffer.join("") === combo.join("")) {
          $dialog[0].showModal();
          $(window).trigger("full/staff-modal/opened");
          buffer = [];
        }
      }
    } else {
      buffer = [];
    }
  });

  $dialog.find(".fsm-header button").on("click", function () {
    $dialog[0].close();
    $(window).trigger("full/staff-modal/closed");
  });

  $dialog.on("submit", "form", function (e) {
    e.preventDefault();

    const $form = $(this);
    const $btn = $form.find("button");
    $btn.addClass("loading");

    $responseContainer.html(
      "O processo de instalação pode demorar um pouquinho, aproveite para tomar um café."
    );

    $.post(ajaxurl, $form.serialize(), function (response) {
      $btn.removeClass("loading");
      $form[0].reset();
      $responseContainer.html(response.data);
    });
  });

  $(window).on("full/staff-modal/opened", function () {
    $repositoryContainer.html("Buscando plugins...");

    $.get(FULL_STAFF.endpoint, function ({ success, data }) {
      if (!success || !data.length) {
        $repositoryContainer.html("Nenhum plugin encontrado");
        return;
      }

      $repositoryContainer.empty();

      for (let i = 0; i < data.length; i++) {
        const item = data[i];
        $repositoryContainer.append(`
          <input type="checkbox" name="plugins[]" value="${item.plugin}" id="plugin-${i}">  
          <label for="plugin-${i}">${item.name}</label>
        `);
      }
    });
  });

  $(window).on("full/staff-modal/closed", function () {
    $repositoryContainer.empty();
    $responseContainer.empty();
  });
});
