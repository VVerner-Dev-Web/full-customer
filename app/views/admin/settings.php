<?php
$full = new FULL_CUSTOMER();

if (isset($_POST['action']) && $_POST['action'] === 'full') :
  $allowBacklink = isset($_POST['allow_backlink']) && $_POST['allow_backlink'];
  $full->set('allow_backlink', $allowBacklink);
endif;

?>


<div class="login-container">
  <div class="col-instructions">
    <a href="https://painel.fullstackagency.club/login/" target="_blank" rel="noopener noreferrer" class="logo-img">
      <img src="<?= fullGetImageUrl('logo-dark.png') ?>" alt="FULL.">
    </a>

    <form id="full-settings" method="POST">
      <input type="hidden" name="action" value="full" readonly>

      <h2>Configurações gerais</h2>

      <label class="toggle-switch" for="allow_backlink">
        <input type="checkbox" name="allow_backlink" value="1" class="toggle-switch-input" id="allow_backlink" <?php checked($full->get('allow_backlink')) ?>>
        <span class="toggle-switch-label">
          <span class="toggle-switch-indicator"></span>
        </span>
        <span class="toggle-switch-content">
          <span style="display: block;">Permitir backlink</span>
          <small class="d-block text-muted">📢 Ao permitir, será inserido um link invisível para o site da FULL em seu site.</small>
        </span>
      </label>

      <button class="full-primary-button" style="margin-top: 2rem;">Salvar configurações</button>
    </form>

  </div>

</div>
</div>
