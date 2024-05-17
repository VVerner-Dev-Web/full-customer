<?php $quota = get_option('full/ai/quota', null) ?>

<script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>

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

          <div class="templately-contents-header" style="flex-direction: column;">
            <div class="templately-contents-header-inner">
              <div class="templately-header-title full-widget-title">
                <h3>FULL.ai - Meta</h3>
                <p>
                  Uso de palavras <span data-quota="used"><?= $quota ? $quota->used : '0' ?></span> de <span data-quota="granted"><?= $quota ? $quota->granted : '0' ?></span>
                </p>
              </div>
            </div>
          </div>

          <div class="full-page-content">

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