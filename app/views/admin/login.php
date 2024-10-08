<?php

use Full\Customer\Login\Settings;

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
                <h3>FULL.login</h3>
              </div>
            </div>
          </div>

          <div class="full-page-content">

            <form method="POST" id="full-login-settings" class="full-widget-form">
              <?php wp_nonce_field('full/widget/login-settings'); ?>
              <input type="hidden" name="action" value="full/widget/login-settings">

              <table>
                <tbody>
                  <tr>
                    <th>
                      <label for="enableChangeLoginUrl">Alterar URL de login</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="enableChangeLoginUrl">
                        <input type="checkbox" name="enableChangeLoginUrl" value="1" class="toggle-switch-input" id="enableChangeLoginUrl" <?php checked($worker->get('enableChangeLoginUrl')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>
                  <tr class="<?php echo $worker->get('enableChangeLoginUrl') ? '' : 'hidden' ?>">
                    <th>
                      <label for="changedLoginUrl">Nova URL</label>
                    </th>
                    <td>
                      <div style="display: flex;align-items: center;gap: 1em;">
                        <span><?php echo home_url('/') ?></span>
                        <input type="text" name="changedLoginUrl" id="changedLoginUrl" value="<?php echo $worker->get('enableChangeLoginUrl') ? $worker->get('changedLoginUrl') : '' ?>" placeholder="entrar" class="custom-input">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <th>
                      <label for="useSiteIdentity">Usar ícone do site na página de login</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="useSiteIdentity">
                        <input type="checkbox" name="useSiteIdentity" value="1" class="toggle-switch-input" id="useSiteIdentity" <?php checked($worker->get('useSiteIdentity')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>
                  <tr>
                    <th>
                      <label for="loginNavMenuItem">Item de login/logout para menus</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="loginNavMenuItem">
                        <input type="checkbox" name="loginNavMenuItem" value="1" class="toggle-switch-input" id="loginNavMenuItem" <?php checked($worker->get('loginNavMenuItem')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>
                  <tr>
                    <th>
                      <label for="redirectAfterLogin">Redirecionamento após login</label>
                    </th>
                    <td>
                      <div style="display: flex;align-items: center;gap: 1em;">
                        <span><?php echo home_url('/') ?></span>
                        <input type="text" name="redirectAfterLogin" id="redirectAfterLogin" class="custom-input" value="<?php echo $worker->get('redirectAfterLogin') ?>">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <th>
                      <label for="redirectAfterLogout">Redirecionamento após logout</label>
                    </th>
                    <td>
                      <div style="display: flex;align-items: center;gap: 1em;">
                        <span><?php echo home_url('/') ?></span>
                        <input type="text" name="redirectAfterLogout" id="redirectAfterLogout" class="custom-input" value="<?php echo $worker->get('redirectAfterLogout') ?>">
                      </div>
                    </td>
                  </tr>
                  <tr>
                  <tr>
                    <th>
                      <button class="full-primary-button">Atualizar</button>
                    </th>
                    <td></td>
                  </tr>
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