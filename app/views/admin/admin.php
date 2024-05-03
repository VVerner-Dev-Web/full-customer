<?php

use Full\Customer\Admin\Settings;

$worker = new Settings();

?>

<div class="full-templates-admin-body">
  <div class="templately-wrapper">
    <div class="templately-header">
      <div class="templately-logo">
        <img src="<?= fullGetImageUrl('logo-novo.png') ?>" alt="Logo FULL">
      </div>
    </div>

    <div class="templately-container templately-pages-container">
      <div class="templately-container-row" id="endpoint-viewport">
        <div class="templately-contents">

          <div class="templately-contents-header">
            <div class="templately-contents-header-inner">
              <div class="templately-header-title full-widget-title">
                <h3>FULL.admin</h3>
              </div>
            </div>
          </div>

          <div class="full-page-content">

            <form method="POST" id="full-admin-settings" class="full-widget-form" style="margin-bottom: 30px">
              <?php wp_nonce_field('full/widget/admin-settings'); ?>
              <input type="hidden" name="action" value="full/widget/admin-settings">

              <table>
                <tbody>
                  <tr>
                    <th>
                      <label for="clearTopBar">Limpar top bar</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="clearTopBar">
                        <input type="checkbox" name="clearTopBar" value="1" class="toggle-switch-input" id="clearTopBar" <?php checked($worker->get('clearTopBar')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>

                  <tr>
                    <th>
                      <label for="disableDashboardWidgets">Desativar widgets da tela inicial</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="disableDashboardWidgets">
                        <input type="checkbox" name="disableDashboardWidgets" value="1" class="toggle-switch-input" id="disableDashboardWidgets" <?php checked($worker->get('disableDashboardWidgets')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>

                  <tr>
                    <th>
                      <label for="hideAdminBarOnFrontend">Ocultar top bar no frontend do site</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="hideAdminBarOnFrontend">
                        <input type="checkbox" name="hideAdminBarOnFrontend" value="1" class="toggle-switch-input" id="hideAdminBarOnFrontend" <?php checked($worker->get('hideAdminBarOnFrontend')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>

                  <tr>
                    <th>
                      <label for="sidebarWidth">Tamanho da barra lateral</label>
                    </th>
                    <td>
                      <select name="sidebarWidth" id="sidebarWidth" style="width: 100%">
                        <?php for ($i = 160; $i <= 300; $i += 20) : ?>
                          <option value="<?= $i ?>" <?php selected($i, $worker->get('sidebarWidth')) ?>>
                            <?= $i ?>px <?= 160 === $i ? '(tamanho padrão)' : '' ?>
                          </option>
                        <?php endfor; ?>
                      </select>
                    </td>
                  </tr>

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