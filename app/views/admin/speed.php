<?php

use Full\Customer\Speed\Settings;

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
                <h3>FULL.speed</h3>
              </div>
            </div>
          </div>

          <div class="full-page-content">

            <form method="POST" id="full-speed-settings" class="full-widget-form" style="margin-bottom: 30px">
              <?php wp_nonce_field('full/widget/speed-settings'); ?>
              <input type="hidden" name="action" value="full/widget/speed-settings">

              <table>
                <tbody>
                  <tr>
                    <th>
                      <label for="disableGutenberg">Reativar editor clássico</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="disableGutenberg">
                        <input type="checkbox" name="disableGutenberg" value="1" class="toggle-switch-input" id="disableGutenberg" <?php checked($worker->get('disableGutenberg')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>
                  <tr>
                    <th>
                      <label for="disableBlockWidgets">Reativar widgets clássicos</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="disableBlockWidgets">
                        <input type="checkbox" name="disableBlockWidgets" value="1" class="toggle-switch-input" id="disableBlockWidgets" <?php checked($worker->get('disableBlockWidgets')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>
                  <tr>
                    <th>
                      <label for="disableDeprecatedComponents">Desativar componentes obsoletos</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="disableDeprecatedComponents">
                        <input type="checkbox" name="disableDeprecatedComponents" value="1" class="toggle-switch-input" id="disableDeprecatedComponents" <?php checked($worker->get('disableDeprecatedComponents')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>

                  <tr>
                    <th>
                      <label for="reduceHeartbeat">Controlar a API Heartbeat</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="reduceHeartbeat">
                        <input type="checkbox" name="reduceHeartbeat" value="1" class="toggle-switch-input" id="reduceHeartbeat" <?php checked($worker->get('reduceHeartbeat')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>

                  <tr>
                    <th>
                      <label for="disablePostRevisions">Desativar revisões</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="disablePostRevisions">
                        <input type="checkbox" name="disablePostRevisions" value="1" class="toggle-switch-input" id="disablePostRevisions" <?php checked($worker->get('disablePostRevisions')) ?>>
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