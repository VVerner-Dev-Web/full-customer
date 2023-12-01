<?php

use Full\Customer\Ai\Settings;

$worker = new Settings();

$quota = get_option('full/ai/quota', null)
?>

<script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>

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

          <div class="templately-contents-header" style="flex-direction: column;">
            <div class="templately-contents-header-inner">
              <div class="templately-header-title full-widget-title">
                <h3>FULL.ai</h3>
                <p>
                  Uso de palavras <span data-quota="used"><?= $quota ? $quota->used : '0' ?></span> de <span data-quota="granted"><?= $quota ? $quota->granted : '0' ?></span>
                </p>
              </div>
            </div>
            <p>Potencialize seu WordPress com suas ferramentas integradas. Simplifique a criação de conteúdo, aprimora meta descrições e enriquece imagens com textos alternativos. Melhore a visibilidade do seu site nos resultados de busca e impulsione seus resultados com a FULL.ai </p>
          </div>

          <div class="full-page-content">
            <div class="tabs">
              <a href="#copywrite-generator" class="active">Gerador de conteúdo</a>
              <a href="#metadescription-generator">Gerador de meta descrição e resumo</a>
              <a href="#image-alt-generator">Gerador de Alt para imagens</a>
            </div>

            <div class="tabbed-content" style="margin-bottom: 30px">
              <form method="POST" id="copywrite-generator" class="full-widget-form">
                <?php wp_nonce_field('full/ai/copywrite-generator'); ?>
                <input type="hidden" name="action" value="full/ai/copywrite-generator">

                <table>
                  <tbody>
                    <tr>
                      <th>
                        <label for="subject">Assunto</label>
                      </th>
                      <td>
                        <input type="text" name="subject" id="subject" value="" class="custom-input" required>
                      </td>
                    </tr>
                    <tr>
                      <th>
                        <label for="seoKeyword">Palavra chave para SEO <small>(opcional)</small></label>
                      </th>
                      <td>
                        <input type="text" name="seoKeyword" id="seoKeyword" value="" class="custom-input">
                      </td>
                    </tr>
                    <tr>
                      <th>
                        <label for="contentSize">Tamanho do conteúdo</label>
                      </th>
                      <td>
                        <select name="contentSize" id="contentSize" class="custom-input" required>
                          <option value="">Selecione</option>
                          <option value="short">Curto - de 300 a 400 palavras</option>
                          <option value="medium">Médio - de 600 a 800 palavras</option>
                          <option value="large">Longo - de 800 a 1200 palavras</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <th>
                        <label for="description">Detalhes</label>
                      </th>
                      <td>
                        <textarea name="description" id="description" cols="30" rows="10" class="custom-input" placeholder="Descreva um pouco o assunto que você deseja abordar" style="min-height: 150px" required></textarea>
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

              <form method="POST" id="copywrite-publish" style="padding: 16px; margin-top: 30px; display: none" class="full-widget-form">
                <?php wp_nonce_field('full/ai/copywrite-publish'); ?>
                <input type="hidden" name="action" value="full/ai/copywrite-publish">
                <input type="text" name="post_title" id="post_title" class="hidden">
                <textarea name="post_content" id="post_content" class="hidden"></textarea>

                <div id="generated-content" style="margin-bottom: 20px">
                  <h1>Lorem ipsum dolor sit amet consectetur adipisicing elit.</h1>

                  <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ratione illum ipsam alias pariatur ab, obcaecati debitis quisquam saepe tenetur earum eos exercitationem similique dolores. Illum, necessitatibus. Perspiciatis ea quasi veniam!</p>
                  <p>Expedita ea hic alias atque impedit numquam omnis debitis tenetur delectus, dolorum natus incidunt odio fuga aut. Eveniet distinctio reiciendis molestias! Quo veniam explicabo sunt labore alias veritatis ad reprehenderit.</p>
                  <p>Recusandae nulla rerum vitae ab architecto, ducimus vel officiis quas libero dolores a placeat dolorum iusto facere error ea, suscipit labore aperiam eveniet. Commodi sint labore voluptas ea tempora possimus.</p>
                  <p>Accusantium cupiditate, dolorum dignissimos libero exercitationem quidem architecto aspernatur sapiente officia non, nobis quaerat ea possimus temporibus deleniti! Aperiam sint consectetur nostrum aut exercitationem praesentium laborum et harum error omnis.</p>
                  <p>Error est veniam, aliquam repellendus magnam suscipit. Incidunt facere aspernatur nam distinctio earum ullam quaerat? Beatae dolores illum, neque quisquam culpa placeat unde facere voluptatem, voluptate ipsam sint nisi doloremque.</p>
                </div>

                <button id="publish-trigger" class="full-primary-button">Criar post com conteúdo</button>

                <div id="copywrite-writing">
                  <dotlottie-player src="https://lottie.host/c747577d-688e-49c6-899d-8eb891b91c05/nSRGmWyp6x.lottie" background="transparent" speed="1" style="width: 350px; height: 350px; margin: auto;" loop autoplay></dotlottie-player>
                </div>
              </form>

              <form method="POST" id="metadescription-generator" class="full-widget-form" style="display: none">
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

              <div id="image-alt-generator" class="full-widget-form" style="padding: 16px; display: none">
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