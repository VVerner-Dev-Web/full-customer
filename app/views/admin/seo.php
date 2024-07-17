<script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>

<?php

use Full\Customer\Seo\Settings;

$worker = new Settings();
$quota = get_option('full/ai/quota', null)

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

            <ul id="analytics-view-nav">
              <li><a href="#powerups">PowerUps</a></li>
              <li><a href="#image-alt">Gerador de Alt para Imagens</a></li>
              <li><a href="#meta-generator">Gerador de MetaDescription</a></li>
            </ul>

            <div class="analytics-view" id="powerups">

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

            <div class="analytics-view" id="image-alt">
              <br>
              <p>
                Uso de palavras <span data-quota="used"><?= $quota ? $quota->used : '0' ?></span> de <span data-quota="granted"><?= $quota ? $quota->granted : '0' ?></span>
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

            <div class="analytics-view" id="meta-generator">
              <br>
              <p>
                Uso de palavras <span data-quota="used"><?= $quota ? $quota->used : '0' ?></span> de <span data-quota="granted"><?= $quota ? $quota->granted : '0' ?></span>
              </p>
              <form method="POST" id="metadescription-generator" class="full-widget-form">
                <?php wp_nonce_field('full/ai/metadescription-generator'); ?>
                <input type="hidden" name="action" value="full/ai/metadescription-generator">

                <table>
                  <tbody>
                    <tr>
                      <th>
                        <label for="postId">Conteúdo</label>
                      </th>
                      <td>
                        <select name="postId" id="postId" class="custom-input" style="width: 100%" required>
                          <option hidden>Carregando...</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <th>
                        <button class="full-primary-button">Gerar conteúdo</button>
                      </th>
                      <td></td>
                    </tr>
                  </tbody>
                </table>
              </form>

              <form method="POST" id="metadesc-publish" style="padding: 16px; margin-top: 30px; display: none" class="full-widget-form">
                <?php wp_nonce_field('full/ai/metadesc-publish'); ?>
                <input type="hidden" name="action" value="full/ai/metadesc-publish">
                <input type="text" name="metadescription" id="metadesc-received" class="hidden">
                <input type="text" name="postId" id="metadesc-postId" class="hidden">

                <div id="metadesc-content" style="margin-bottom: 20px"></div>

                <button id="metadesc-trigger" class="full-primary-button">Atualizar post</button>

                <div id="metadesc-writing">
                  <dotlottie-player src="https://lottie.host/c747577d-688e-49c6-899d-8eb891b91c05/nSRGmWyp6x.lottie" background="transparent" speed="1" style="width: 350px; height: 350px; margin: auto;" loop autoplay></dotlottie-player>
                </div>
              </form>
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
