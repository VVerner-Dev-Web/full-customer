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
                <h3>FULL.ai</h3>
                <p>
                  Uso de palavras <span data-quota="used"><?= $quota ? $quota->used : '0' ?></span> de <span data-quota="granted"><?= $quota ? $quota->granted : '0' ?></span>
                </p>
              </div>
            </div>
          </div>

          <div class="full-page-content">
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

          </div>
        </div>

      </div>
    </div>
  </div>
</div>