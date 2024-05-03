<?php

use Full\Customer\Content\Settings;

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
                <h3>FULL.content</h3>
              </div>
            </div>
          </div>

          <div class="full-page-content">

            <form method="POST" id="full-content-settings" class="full-widget-form">
              <?php wp_nonce_field('full/widget/content-settings'); ?>
              <input type="hidden" name="action" value="full/widget/content-settings">

              <table>
                <tbody>

                  <tr>
                    <th>
                      <label for="enableContentDuplication">Duplicação conteúdo</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="enableContentDuplication">
                        <input type="checkbox" name="enableContentDuplication" value="1" class="toggle-switch-input" id="enableContentDuplication" <?php checked($worker->get('enableContentDuplication')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>

                  <tr>
                    <th>
                      <label for="disableComments">Desativar comentários em todo o site</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="disableComments">
                        <input type="checkbox" name="disableComments" value="1" class="toggle-switch-input" id="disableComments" <?php checked($worker->get('disableComments')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>

                  <tr>
                    <th>
                      <label for="redirect404ToHomepage">Redirecionar erros 404 para a página inicial</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="redirect404ToHomepage">
                        <input type="checkbox" name="redirect404ToHomepage" value="1" class="toggle-switch-input" id="redirect404ToHomepage" <?php checked($worker->get('redirect404ToHomepage')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>

                  <tr>
                    <th>
                      <label for="openExternalLinkInNewTab">Abrir links de outros sites em nova guia</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="openExternalLinkInNewTab">
                        <input type="checkbox" name="openExternalLinkInNewTab" value="1" class="toggle-switch-input" id="openExternalLinkInNewTab" <?php checked($worker->get('openExternalLinkInNewTab')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>

                  <tr>
                    <th>
                      <label for="publishMissingSchedulePosts">Publicar automaticamente posts que perderam agendamento</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="publishMissingSchedulePosts">
                        <input type="checkbox" name="publishMissingSchedulePosts" value="1" class="toggle-switch-input" id="publishMissingSchedulePosts" <?php checked($worker->get('publishMissingSchedulePosts')) ?>>
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