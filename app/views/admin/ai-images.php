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
                <h3>FULL.ai - Images</h3>
                <p>
                  Uso de palavras <span data-quota="used"><?= $quota ? $quota->used : '0' ?></span> de <span data-quota="granted"><?= $quota ? $quota->granted : '0' ?></span>
                </p>
              </div>
            </div>
          </div>

          <div class="full-page-content">
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