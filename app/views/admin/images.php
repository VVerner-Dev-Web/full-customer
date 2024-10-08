<?php

use Full\Customer\Images\ImageOptimization;
use Full\Customer\Images\Settings;

$worker = new Settings();
$quota = get_option('full/ai/quota', null)

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
                <h3>FULL.images</h3>
              </div>
            </div>
          </div>

          <div class="full-page-content">

            <ul id="analytics-view-nav" class="full-tab-nav">
              <li><a href="#optimization">Otimização de imagens</a></li>
              <li><a href="#image-alt">Gerador de Alt para Imagens</a></li>
            </ul>

            <div class="full-tab-panel analytics-view" id="optimization">
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

                    <tr class="resize <?php echo $worker->get('enableUploadResize') ? '' : 'hidden' ?>">
                      <th>
                        <label for="resizeMaxSize">Tamanho máximo (em pixel)</label>
                      </th>
                      <td>
                        <input type="text" name="resizeMaxSize" id="resizeMaxSize" value="<?php echo $worker->get('enableUploadResize') ? $worker->get('resizeMaxSize') : '' ?>" class="custom-input">
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
                          <?php echo $usage->done . _n(' imagem', ' imagens', $usage->done) ?>
                          <?php if ($usage->optimization > 0) : ?>
                            - redução total de <?php echo $usage->readableOptimization ?>
                          <?php endif; ?>
                        </td>
                      </tr>
                      <tr>
                        <th>Créditos disponíveis</th>
                        <td><?php echo $usage->available . _n(' crédito', ' créditos', $usage->available) ?></td>
                      </tr>
                    </tbody>
                  </table>

                </div>

              <?php endif; ?>
            </div>

            <div class="full-tab-panel analytics-view" id="image-alt">
              <br>
              <p>
                Uso de palavras <span data-quota="used"><?php echo $quota ? $quota->used : '0' ?></span> de <span data-quota="granted"><?php echo $quota ? $quota->granted : '0' ?></span>
              </p>

              <div id="image-alt-generator" class="full-widget-form" style="padding: 16px">
                <p>O atributo "alt" para as imagens define um texto descritivo para a imagem em questão. Ele é extremamente necessário para a acessibilidade do seu site e também pode causar impacto positivo no SEO dos seus conteúdos.</p>

                <div id="images-response"></div>

                <div style="text-align: center; margin-top: 30px;">
                  <span class="images-pagination"></span><br>
                  <button type="button" data-page="1" id="search-images-missing-alt" class="full-primary-button">Procurar imagens sem alt text</button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/template" id="template-image-missing-alt">
  <form class="image-card alt-form">
    <input type="hidden" class="attachmentId" value="{id}">
    <a href="{url}" target="_blank" rel="noopener noreferrer">
      <img src="{url}">
    </a>
    <div class="image-content">
      <textarea rows="2" class="custom-input alt-input" placeholder="Atributo ALT para ser usado na imagem" rows="2" required></textarea>
      <button type="button" class="full-primary-button generate-image-alt" >Gerar conteúdo</button>
      <button type="submit" class="full-primary-button update-image-alt"  style="display: none">Atualizar</button>
    </div>
  </form>
</script>