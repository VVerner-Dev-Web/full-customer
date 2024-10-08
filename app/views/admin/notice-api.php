<div id="full-notice-api">
  <p></p>
  <div class="buttons">
    <a href="" data-action="view" id="open-full-notice-api" target="_blank" rel="noopener noreferrer" style="display: none">Ver</a>
    <a href="#!" data-action="dismiss" id="dismiss-full-notice-api">Fechar</a>
  </div>
</div>

<template id="full-notice-assets">
  <style id="full-notice-styles">
    #full-notice-api {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 99999;
      background: #eabc32;
      height: 70px;
      padding: 10px;
      box-sizing: border-box;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #000;
      font-weight: bold;
      flex-direction: column;
      gap: 10px;
    }

    #wpwrap {
      margin-top: 70px;
    }

    #full-notice-api p {
      margin: 0;
      line-height: 1;
    }

    #wpadminbar {
      top: 70px;
    }

    #full-notice-api .buttons {
      display: flex;
      gap: 10px;
      width: 150px;
      justify-content: center;
      align-items: center;
      text-align: center;
    }

    #full-notice-api .buttons a {
      text-decoration: none;
      border: solid 1px #000;
      color: #000;
      border-radius: 3px;
      padding: 2px 0;
      flex: 1;
      display: block;
      font-size: 90%;
      transition: all 150ms;
    }

    #full-notice-api .buttons a:hover {
      background: #000;
      color: #fff;
    }
  </style>
</template>

<script id="full-notice-api-script">
  jQuery(function($) {
    const $container = $('#full-notice-api');
    const apiUrl = '<?php echo esc_url(fullCustomer()->getFullDashboardApiUrl() . '-customer/v1/alerts') ?>'

    $.get(apiUrl, function(data) {
      if (!data.message || localStorage.getItem('full-notice/' + data.id)) {
        return;
      }

      $container.find('p').text(data.message);
      $container.data('id', data.id);

      if (data.url) {
        $('#open-full-notice-api').attr('href', data.url).show();
      }

      $('body').append($('#full-notice-assets').html());
    })

    $('#open-full-notice-api, #dismiss-full-notice-api').on('click', function() {
      $container.hide();

      $('#full-notice-styles').remove();

      const data = {
        id: $container.data('id'),
        action: $(this).data('action')
      }

      $.post(apiUrl, data);

      localStorage.setItem('full-notice/' + data.id, 1);
    })
  });
</script>