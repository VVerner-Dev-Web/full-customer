<?php

use Full\Customer\Images\ImageOptimization;
use Full\Customer\Images\Settings;

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
                <h3>FULL.images</h3>
              </div>
            </div>
          </div>

          <div class="full-page-content">

            <h3>Otimização de imagens</h3>
            <form method="POST" id="full-images-settings" class="full-widget-form" style="margin-bottom: 30px">
              <?php wp_nonce_field('full/widget/image-settings'); ?>
              <input type="hidden" name="action" value="full/widget/image-settings">

              <table>
                <tbody>
                  <tr>
                    <th>
                      <label for="useImagify">Comprimir (reduzir o peso) de novos uploads?</label>
                      <a href="http://" target="_blank" rel="noopener noreferrer">Saiba mais</a>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="useImagify">
                        <input type="checkbox" name="useImagify" value="1" class="toggle-switch-input" id="useImagify" <?php checked($worker->get('useImagify')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>

                  <tr>
                    <th>
                      <label for="enableUploadResize">Redimensionar novos uploads?</label>
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
                      <label for="resizeMaxSize">Tamanho máximo (em pixel)</label>
                    </th>
                    <td>
                      <input type="text" name="resizeMaxSize" id="resizeMaxSize" value="<?= $worker->get('enableUploadResize') ? $worker->get('resizeMaxSize') : '' ?>" class="custom-input">
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

            <?php if ($worker->get('useImagify')) :
              $usage = ImageOptimization::getUsage();
            ?>

              <h3>Status de compressões</h3>
              <div class="full-widget-form" style="margin-bottom: 30px">

                <table>
                  <tbody>
                    <tr>
                      <th>Imagens otimizadas</th>
                      <td>
                        <?= $usage->done . _n(' imagem', ' imagens', $usage->done) ?>
                        <?php if ($usage->optimization > 0) : ?>
                          - redução total de <?= $usage->readableOptimization ?>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <tr>
                      <th>Créditos disponíveis</th>
                      <td><?= $usage->available . _n(' crédito', ' créditos', $usage->available) ?></td>
                    </tr>
                  </tbody>
                </table>

              </div>

            <?php endif; ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>