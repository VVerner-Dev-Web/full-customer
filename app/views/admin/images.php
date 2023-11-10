<?php

use Full\Customer\Images\Settings;

$worker = new Settings();

?>

<div class="full-templates-admin-body">
  <div class="templately-wrapper">
    <div class="templately-header">
      <div class="templately-logo">
        <img src="<?= fullGetImageUrl('logo-dark-2.png') ?>" alt="Logo FULL">
      </div>
    </div>

    <div class="templately-container templately-pages-container">
      <div class="templately-container-row" id="endpoint-viewport">
        <div class="templately-contents">

          <div class="templately-contents-header">
            <div class="templately-contents-header-inner">
              <div class="templately-header-title">
                <h3>FULL.imagem</h3>
              </div>
            </div>
          </div>

          <div class="full-page-content">

            <form method="POST" id="full-images-settings" class="full-widget-form">
              <?php wp_nonce_field('full/widget/image-settings'); ?>
              <input type="hidden" name="action" value="full/widget/image-settings">

              <table>
                <tbody>
                  <tr>
                    <th>
                      <label for="enableUploadResize">Otimizar novos uploads?</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="enableUploadResize">
                        <input type="checkbox" name="enableUploadResize" value="1" class="toggle-switch-input" id="enableUploadResize" <?php checked($worker->get('enableUploadResize')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>

                  <tr class="resize <?= $worker->get('enableUploadResize') ? '' : 'hidden' ?>">
                    <th>
                      <label for="resizeMaxWidth">Largura máxima (em pixel)</label>
                    </th>
                    <td>
                      <input type="text" name="resizeMaxWidth" id="resizeMaxWidth" value="<?= $worker->get('enableUploadResize') ? $worker->get('resizeMaxWidth') : '' ?>" class="custom-input">
                    </td>
                  </tr>

                  <tr class="resize <?= $worker->get('enableUploadResize') ? '' : 'hidden' ?>">
                    <th>
                      <label for="resizeMaxHeight">Altura máxima (em pixel)</label>
                    </th>
                    <td>
                      <input type="text" name="resizeMaxHeight" id="resizeMaxHeight" value="<?= $worker->get('enableUploadResize') ? $worker->get('resizeMaxHeight') : '' ?>" class="custom-input">
                    </td>
                  </tr>

                  <tr class="resize <?= $worker->get('enableUploadResize') ? '' : 'hidden' ?>">
                    <th>
                      <label for="resizeQuality">Qualidade da imagem (0 a 100)</label>
                    </th>
                    <td>
                      <input type="number" name="resizeQuality" min="0" step="1" max="100" id="resizeQuality" value="<?= $worker->get('enableUploadResize') ? $worker->get('resizeQuality') : '' ?>" class="custom-input">
                    </td>
                  </tr>

                  <tr>
                    <th>
                      <label for="enableSvgUpload">Permitir upload de .SVG?</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="enableSvgUpload">
                        <input type="checkbox" name="enableSvgUpload" value="1" class="toggle-switch-input" id="enableSvgUpload" <?php checked($worker->get('enableSvgUpload')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>

                  <tr>
                    <th>
                      <label for="enableMediaReplacement">Sobrescrever imagens</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="enableMediaReplacement">
                        <input type="checkbox" name="enableMediaReplacement" value="1" class="toggle-switch-input" id="enableMediaReplacement" <?php checked($worker->get('enableMediaReplacement')) ?>>
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