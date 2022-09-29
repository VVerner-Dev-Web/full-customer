<?php $full = new FULL_CUSTOMER_Env(); ?>

<div class="login-container">
  <div class="col-instructions">
    <a href="https://painel.fullstackagency.club/login/" target="_blank" rel="noopener noreferrer" class="logo-img">
      <img src="<?= fullGetImageUrl('logo-dark.png') ?>" alt="FULL.">
    </a>

    <img src="<?= fullGetImageUrl('wordpress.svg') ?>" alt="WordPress" class="wordpress-img">

    <div class="instructions-text">
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
    </div>

  </div>

  <div class="col-login">
    <?php if ($full->isConnected()) : ?>

      <div id="full-connect">
        <h2>Site conectado!</h2>

        <p>Você está conectado com os dados abaixo:</p>

        <ul class="checkmark-list">
          <li>
            <strong>Usuário no painel</strong>
            <span><?= $full->get('connection_email') ?></span>
          </li>
        </ul>

        <a href="<?= $full->get('dashboard_url') ?>" class="full-primary-button" target="_blank" rel="noopener noreferrer">Acessar painel</a>
      </div>

    <?php else : ?>
      <form id="full-connect">
        <h2>Conectar</h2>
        <label for="customer-email">
          <span>Seu e-mail no painel</span>
          <input placeholder="Insira seu e-mail de acesso" type="email" name="email" id="customer-email" required>
        </label>

        <label for="customer-password" style="display: none; margin-top: 1rem">
          <span>Sua senha</span>
          <input placeholder="Insira a senha de acesso ao painel WP" type="password" name="password" id="customer-password">
        </label>

        <button class="full-primary-button">Realizar conexão</button>
      </form>
    <?php endif; ?>
  </div>
</div>
