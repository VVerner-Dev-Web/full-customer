(function( $ ) {
	'use strict';

  const FULL = full_localize;

  const $connectForm = $('#full-connect');

  $connectForm.on('submit', function(e){
    e.preventDefault();

    const dashboardEmail = $connectForm.find('#customer-email').val();
    const wpUserPassword = $connectForm.find('#customer-password').val();

    $connectForm.find('button').addClass('loading');

    if (wpUserPassword) {
      connectSite(dashboardEmail, FULL.user_login, wpUserPassword, 'user_password')
      .then(response => response.json())
      .then(response => {
        $connectForm.find('button').removeClass('loading');
        handleSiteConnectionResponse(response);
      })

    } else {
      generateApplicationPassword()
        .then(response => response.json())
        .then(response => {
          if (response.code === 'application_passwords_disabled') {
            fireAlert('error', 'As senhas de aplicação estão indisponíveis em seu site. Por favor, informe a senha do seu usuário administrador do WordPress.');
            showCustomerPasswordInput();

            $connectForm.find('button').removeClass('loading');
            return;
          }

          const {password} = response;

          connectSite(dashboardEmail, FULL.user_login, password, 'application_password')
          .then(response => response.json())
          .then(response => {
            $connectForm.find('button').removeClass('loading');
            handleSiteConnectionResponse(response);
          })
        })
    }
  })

  const showCustomerPasswordInput = () => {
    $('label[for="customer-password"]').css('display', 'block');
    $('#customer-password').attr('required', true).prop('required', true)
  }

  const generateApplicationPassword = () => {
    const endpoint = 'wp/v2/users/me/application-passwords';
    const request   = {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': FULL.auth
      },
      body: JSON.stringify({
        name: 'Conexão com painel FULL id:' + Math.ceil(Math.random() * 1000)
      })
    }

    return fetch(FULL.rest_url + endpoint, request);
  }

  const connectSite = (dashboardEmail, user, password, password_origin) => {
    const endpoint = 'connect-site';
    const request   = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        user: user,
        password: password,
        password_origin: password_origin,
        email: dashboardEmail
      })
    }

    return fetch(FULL.dashboard_url + endpoint, request);
  }

  const handleSiteConnectionResponse = response => {
    if (response.success) {
      const endpoint = 'full-customer/connect';
      const request   = {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': FULL.auth
        },
        body: JSON.stringify({
          email: response.connection_email,
          dashboard_url: response.dashboard_url
        })
      }

      fetch(FULL.rest_url + endpoint, request);

      fireAlert('success', 'Site conectado com sucesso!').then(() => {
        location.reload();
      });
    } else if (response.code === 'user_not_found') {
      fireAlert('warning', 'O email que você informou não está cadastrado na FULL.');
      return;
    } else if (response.code === 'site_already_connected') {
      fireAlert('warning', 'Este site já foi conectado anteriormente no painel da FULL.');
      return;
    } else {
      fireAlert('error', 'Algo deu errado, tente conectar o site diretamente pelo painel da FULL.');
      return;
    }
  }

  const fireAlert = (type, message) => {
    const titles = {
      success : '🎉 Tudo certo',
      error : '📢 Algo deu errado',
      warning : '🧐 Ei',
    }

    return Swal.fire({
      titleText: titles[type],
      text: message,
    })
  }
})( jQuery );
