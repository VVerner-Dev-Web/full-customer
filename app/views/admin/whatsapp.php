<?php

use Full\Customer\WhatsApp\Settings;

$worker = new Settings();

?>

<div class="full-templates-admin-body">
  <div class="templately-wrapper">
    <div class="templately-header">
      <div class="templately-logo">
        <img src="<?php echo esc_url(fullGetImageUrl('logo-novo.png')) ?>" alt="Logo FULL">
      </div>
    </div>

    <div class="templately-container templately-pages-container">
      <div class="templately-container-row" id="endpoint-viewport">
        <div class="templately-contents">

          <div class="templately-contents-header">
            <div class="templately-contents-header-inner">
              <div class="templately-header-title full-widget-title">
                <h3>FULL.whatsapp</h3>
              </div>
            </div>
          </div>

          <div class="full-page-content">

            <form method="POST" id="full-email-settings" class="full-widget-form" style="margin-bottom: 30px">
              <?php wp_nonce_field('full/widget/whatsapp-settings'); ?>
              <input type="hidden" name="action" value="full/widget/whatsapp-settings">

              <table>
                <tbody>
                  <tr>
                    <th>
                      <label for="enableGlobalButton">Exibir botão flutuante no site?</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="enableGlobalButton">
                        <input type="checkbox" name="enableGlobalButton" value="1" class="toggle-switch-input" id="enableGlobalButton" <?php checked($worker->isButtonEnabled()) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>

                  <tr class="whatsapp <?php echo $worker->isButtonEnabled() ? '' : 'hidden' ?>">
                    <th>
                      <label for="whatsappNumber">Número de telefone</label>
                    </th>
                    <td>
                      <input type="text" inputmode="numeric" name="whatsappNumber" id="whatsappNumber" value="<?php echo $worker->isButtonEnabled() ? $worker->get('whatsappNumber') : '' ?>" class="custom-input">
                    </td>
                  </tr>

                  <tr class="whatsapp <?php echo $worker->isButtonEnabled() ? '' : 'hidden' ?>">
                    <th>
                      <label for="whatsappMessage">Mensagem padrão para envio</label>
                    </th>
                    <td>
                      <input type="text" name="whatsappMessage" id="whatsappMessage" value="<?php echo $worker->isButtonEnabled() ? $worker->get('whatsappMessage') : '' ?>" class="custom-input">
                    </td>
                  </tr>

                  <tr class="whatsapp <?php echo $worker->isButtonEnabled() ? '' : 'hidden' ?>">
                    <th>
                      <label for="whatsappLogoSize">Tamanho do ícone (em pixels)</label>
                    </th>
                    <td>
                      <input type="number" step="1" min="0" name="whatsappLogoSize" id="whatsappLogoSize" value="<?php echo $worker->isButtonEnabled() ? $worker->get('whatsappLogoSize') : '' ?>" class="custom-input">
                    </td>
                  </tr>

                  <tr class="whatsapp <?php echo $worker->isButtonEnabled() ? '' : 'hidden' ?>">
                    <th>
                      <label for="whatsappPosition">Posição na tela</label>
                    </th>
                    <td>
                      <select name="whatsappPosition" id="whatsappPosition" class="custom-input">
                        <option value="bottom-left" <?php selected('bottom-left', $worker->get('whatsappPosition')) ?>>Inferior, lado esquerdo</option>
                        <option value="bottom-center" <?php selected('bottom-center', $worker->get('whatsappPosition')) ?>>Inferior, centro</option>
                        <option value="bottom-right" <?php selected('bottom-right', $worker->get('whatsappPosition')) ?>>Inferior, lado direito</option>
                      </select>
                    </td>
                  </tr>

                  <tr class="whatsapp <?php echo $worker->isButtonEnabled() ? '' : 'hidden' ?>">
                    <th>
                      <label for="displayCondition">Local de exibição</label>
                    </th>
                    <td>
                      <select name="displayCondition" id="displayCondition" class="custom-input">
                        <option value="global" <?php selected('global', $worker->get('displayCondition')) ?>>Exibir em todo o site</option>
                        <option value="cpt" <?php selected('cpt', $worker->get('displayCondition')) ?>>Exibir por tipo de conteúdo</option>
                        <option value="custom" <?php selected('custom', $worker->get('displayCondition')) ?>>Exibir por conteúdo</option>
                      </select>
                      <small></small>
                    </td>
                  </tr>

                  <tr class="whatsapp">
                    <th>
                      Dica!
                    </th>
                    <td>Na página de edição do conteúdo, procure por "FULL.whatsapp" e controle a visibilidade do botão</td>
                  </tr>

                  <tr class="displayConditionCpt <?php echo $worker->get('displayCondition') === 'cpt' ? '' : 'hidden' ?>">
                    <th style="vertical-align: top">
                      <label for="validCpt">Local de exibição</label>
                    </th>
                    <td>
                      <?php foreach (get_post_types(['public' => true], 'objects') as $cpt) : ?>
                        <label class="toggle-switch toggle-switch-sm" for="validCpt-<?php echo $cpt->name ?>">
                          <input type="checkbox" name="validCpt[]" value="<?php echo $cpt->name ?>" class="toggle-switch-input" id="validCpt-<?php echo $cpt->name ?>" <?php checked($worker->isButtonEnabledForPostType($cpt->name)) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                          <span class="toggle-switch-content">
                            <?php echo $cpt->label ?>
                          </span>
                        </label>
                      <?php endforeach; ?>
                    </td>
                  </tr>

                  <tr class="whatsapp <?php echo $worker->isButtonEnabled() ? '' : 'hidden' ?>">
                    <th>
                      <label for="whatsappLogo">Ícone para exibição</label>
                    </th>
                    <td>
                      <div class="icons-container">
                        <?php for ($i = 1; $i <= 3; $i++) :  $pad = str_pad($i, 3, '0', STR_PAD_LEFT) ?>
                          <input type="radio" name="whatsappLogo" id="whatsappLogo-<?php echo $pad ?>" value="<?php echo $pad ?>" <?php checked($pad, $worker->get('whatsappLogo')) ?>>
                          <label for="whatsappLogo-<?php echo $pad ?>">
                            <img src="<?php echo $worker->getLogoUrl($pad) ?>" alt="Logo do WhatsApp">
                          </label>
                        <?php endfor; ?>
                      </div>
                    </td>
                  </tr>

                  <tr>
                    <th>
                      <button class="full-primary-button">Atualizar</button>
                    </th>
                    <td></td>
                  </tr>
                </tbody>
              </table>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .icons-container {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    gap: 2em;
  }

  .icons-container label {
    display: block;
    padding: .5em;
    border: 0.0625rem solid #c3c6d1 !important;
    border-radius: 0.3125rem;
    line-height: 1;
    filter: grayscale(1);
    transition: 150ms;
  }

  .icons-container input:checked+label {
    border-color: #05ab2e !important;
    filter: grayscale(0);
  }

  .icons-container input {
    display: none;
  }
</style>