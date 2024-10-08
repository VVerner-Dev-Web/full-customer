<?php $full = fullCustomer(); ?>

<div class="wrap full-customer-page" id="fc-connection">
  <div class="login-container">
    <div class="col-instructions">
      <a href="<?php echo esc_url($full->getBranding('plugin-author-url', 'https://painel.full.services/login/')) ?>" target="_blank" rel="noopener noreferrer" class="logo-img">
        <img src="<?php echo esc_url($full->getBranding('admin-page-logo-url', fullGetImageUrl('logo-novo.png'))) ?>" alt="<?php echo esc_html($full->getBranding('plugin-author', 'FULL.')) ?>">
      </a>

      <img src="<?php echo esc_url(fullGetImageUrl('wordpress.svg')) ?>" alt="WordPress" class="wordpress-img">

      <div class="instructions-text">
        <?php ob_start(); ?>
        <h2>Facilite a gestão do seu WordPress</h2>

        <ul class="checkmark-list">
          <li>
            <strong>Plugins e temas</strong>
            <span>Atualize, remova e ative plugins e temas premium</span>
          </li>
          <li>
            <strong>Segurança e performance</strong>
            <span>Controle o uptime e segurança do seu site diretamente do dashboard</span>
          </li>
        </ul>
        <?php echo wp_kses_post($full->getBranding('admin-page-content', ob_get_clean())); ?>
      </div>

    </div>

    <div class="col-login">
      <?php if (fullIsCorrectlyConnected()) : ?>

        <div id="full-connect" class="full-form">
          <h2>
            Site conectado!
          </h2>

          <p>Aproveite o melhor que a FULL. tem para aproveitar em seu site </p>
          <a href="<?php echo esc_url($full->get('dashboard_url')) ?>" class="full-primary-button full-button-block" target="_blank" rel="noopener noreferrer" style="margin-top: 1rem">Acessar painel</a>
        </div>

      <?php else : ?>

        <?php if ($full->hasDashboardUrl()) : ?>
          <div class="full-disconnected-notice">
            📢 Site desconectado, conecte-o novamente abaixo.
          </div>
          <?php $full->set('dashboard_url', null); ?>
        <?php endif; ?>

        <ul id="form-nav" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" href="#full-connect">Conectar site</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#full-register">Criar conta</a>
          </li>
        </ul>

        <form id="full-connect" class="full-form form-nav-toggle">
          <h2>Conectar</h2>
          <label for="customer-email">
            <span>Seu e-mail no painel FULL.</span>
            <input placeholder="Insira seu e-mail de acesso" type="email" name="email" id="customer-email" autocomplete="email" required>
          </label>

          <label for="customer-password" style="display: none; margin-top: 1rem">
            <span>Sua senha no WordPress</span>
            <input placeholder="Insira a senha de acesso ao painel WP" type="password" name="password" id="customer-password" autocomplete="off">
          </label>

          <button class="full-primary-button full-button-block">Realizar conexão</button>
        </form>

        <form id="full-register" class="full-form form-nav-toggle" style="display: none">
          <h2>Cadastro na FULL.</h2>

          <label for="register-email">
            <span>Seu nome</span>
            <input type="text" name="name" id="register-name" autocomplete="name" required>
          </label>

          <label for="register-email" style="margin-top: 1rem">
            <span>Seu e-mail</span>
            <input type="email" name="email" id="register-email" autocomplete="email" required>
          </label>

          <label for="register-password" style="margin-top: 1rem">
            <span>Senha</span>
            <input type="password" name="password" id="register-password" autocomplete="new-password">
          </label>

          <label class="toggle-switch" for="register-try_connect" style="margin-top: 1rem">
            <input type="checkbox" name="try_connect" value="1" class="toggle-switch-input" id="register-try_connect">
            <span class="toggle-switch-label">
              <span class="toggle-switch-indicator"></span>
            </span>
            <span class="toggle-switch-content">
              <span style="display: block;">Conectar site</span>
              <small class="d-block text-muted">Tentar conectar o site automaticamente após registro na FULL.</small>
            </span>
          </label>

          <button class="full-primary-button full-button-block">Realizar conexão</button>
        </form>
      <?php endif; ?>

      <div id="full-connection-validate">
        <a href="<?php echo esc_url(admin_url('admin.php?page=full-connection&full=verify_license')) ?>">
          Verificar licença PRO
        </a>
      </div>

    </div>
  </div>
</div>