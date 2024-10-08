<?php

use Full\Customer\Email\Settings;

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
                <h3>FULL.smtp</h3>
              </div>
            </div>
          </div>

          <div class="full-page-content">

            <form method="POST" id="full-email-settings" class="full-widget-form" style="margin-bottom: 30px">
              <?php wp_nonce_field('full/widget/email-settings'); ?>
              <input type="hidden" name="action" value="full/widget/email-settings">

              <table>
                <tbody>
                  <tr>
                    <th>
                      <label for="senderName">Forçar nome do remetente</label>
                    </th>
                    <td>
                      <input type="text" name="senderName" id="senderName" value="<?php echo esc_html($worker->get('senderName')) ?>" class="custom-input">
                    </td>
                  </tr>
                  <tr>
                    <th>
                      <label for="senderEmail">Forçar email do remetente</label>
                    </th>
                    <td>
                      <input type="email" name="senderEmail" id="senderEmail" value="<?php echo esc_html($worker->get('senderEmail')) ?>" class="custom-input">
                    </td>
                  </tr>

                  <tr>
                    <th>
                      <label for="enableSmtp">Ativar configuração SMTP</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="enableSmtp">
                        <input type="checkbox" name="enableSmtp" value="1" class="toggle-switch-input" id="enableSmtp" <?php checked($worker->get('enableSmtp')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>
                  <tr class="smtp <?php echo esc_html($worker->get('enableSmtp') ? '' : 'hidden') ?>">
                    <th>
                      <label for="smtpHost">Servidor</label>
                    </th>
                    <td>
                      <input type="text" name="smtpHost" id="smtpHost" value="<?php echo $worker->get('enableSmtp') ? $worker->get('smtpHost') : '' ?>" class="custom-input">
                    </td>
                  </tr>
                  <tr class="smtp <?php echo esc_html($worker->get('enableSmtp') ? '' : 'hidden') ?>">
                    <th>
                      <label for="smtpPort">Porta</label>
                    </th>
                    <td>
                      <input type="number" step="1" name="smtpPort" id="smtpPort" value="<?php echo $worker->get('enableSmtp') ? $worker->get('smtpPort') : '' ?>" class="custom-input">
                    </td>
                  </tr>
                  <tr class="smtp <?php echo esc_html($worker->get('enableSmtp') ? '' : 'hidden') ?>">
                    <th>
                      <label for="smtpSecurity">Criptografia</label>
                    </th>
                    <td>
                      <select name="smtpSecurity" id="smtpSecurity" class="custom-input">
                        <option value="none" <?php selected('none', $worker->get('smtpSecurity')) ?>>Nenhuma</option>
                        <option value="ssl" <?php selected('ssl', $worker->get('smtpSecurity')) ?>>SSL</option>
                        <option value="tls" <?php selected('tls', $worker->get('smtpSecurity')) ?>>TLS</option>
                      </select>
                    </td>
                  </tr>
                  <tr class="smtp <?php echo esc_html($worker->get('enableSmtp') ? '' : 'hidden') ?>">
                    <th>
                      <label for="smtpUser">Usuário</label>
                    </th>
                    <td>
                      <input type="text" name="smtpUser" id="smtpUser" value="<?php echo $worker->get('enableSmtp') ? $worker->get('smtpUser') : '' ?>" class="custom-input">
                    </td>
                  </tr>
                  <tr class="smtp <?php echo esc_html($worker->get('enableSmtp') ? '' : 'hidden') ?>">
                    <th>
                      <label for="smtpPassword">Senha</label>
                    </th>
                    <td>
                      <input type="password" name="smtpPassword" id="smtpPassword" value="<?php echo $worker->get('enableSmtp') ? $worker->get('smtpPassword') : '' ?>" class="custom-input" autocomplete="off">
                    </td>
                  </tr>
                  <tr class="smtp <?php echo esc_html($worker->get('enableSmtp') ? '' : 'hidden') ?>">
                    <th>
                      <label for="smtpDebug">Ativar debug?</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="smtpDebug">
                        <input type="checkbox" name="smtpDebug" value="1" class="toggle-switch-input" id="smtpDebug" <?php checked($worker->get('smtpDebug')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
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

            <h3>Envio de email de teste</h3>
            <form method="POST" id="full-email-test" class="full-widget-form">
              <?php wp_nonce_field('full/widget/email-test'); ?>
              <input type="hidden" name="action" value="full/widget/email-test">

              <table>
                <tbody>
                  <tr>
                    <th>
                      <label for="recipient">Destinatário</label>
                    </th>
                    <td>
                      <input type="email" name="recipient" id="recipient" value="" class="custom-input" required>
                    </td>
                  </tr>

                  <tr>
                    <th>
                      <button class="full-primary-button">Enviar</button>
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