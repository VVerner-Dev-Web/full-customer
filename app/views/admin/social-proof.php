<?php

$env = new Full\Customer\SocialProof\Settings();

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
                <h3>FULL.social proof</h3>
              </div>
            </div>
          </div>

          <div class="full-page-content">
            <ul id="analytics-view-nav" class="full-tab-nav">
              <?php if (defined('WC_PLUGIN_FILE')) : ?>
                <li><a href="#recent-purchases">Compras recentes</a></li>
              <?php endif; ?>

              <li><a href="#product-visitors">Visitas recentes</a></li>
            </ul>

            <?php if (defined('WC_PLUGIN_FILE')) : ?>
              <div class="analytics-view full-tab-panel " id="recent-purchases">
                <form method="POST" id="full-social-proof-settings" class="full-widget-form">

                  <p>Este popup exibirá informações das últimas compras realizadas na loja.</p>

                  <?php wp_nonce_field('full/widget/social-proof/purchases'); ?>
                  <input type="hidden" name="action" value="full/widget/social-proof/purchases">

                  <table>
                    <tbody>
                      <tr>
                        <th>
                          <label for="enableWooCommerceOrdersPopup">Ativar?</label>
                        </th>
                        <td>
                          <label class="toggle-switch toggle-switch-sm" for="enableWooCommerceOrdersPopup">
                            <input type="checkbox" name="enableWooCommerceOrdersPopup" value="1" <?= checked($env->get('enableWooCommerceOrdersPopup')) ?> class="toggle-switch-input" id="enableWooCommerceOrdersPopup">
                            <span class="toggle-switch-label">
                              <span class="toggle-switch-indicator"></span>
                            </span>
                          </label>
                        </td>
                      </tr>
                      <tr>
                        <th>
                          <label for="ordersPopupPosition">Posição do popup</label>
                        </th>
                        <td>
                          <select name="ordersPopupPosition" id="ordersPopupPosition" class="custom-input">
                            <option <?php selected('bottom-left', $env->get('ordersPopupPosition')) ?> value="bottom-left">Inferior, lado esquerdo</option>
                            <option <?php selected('bottom-center', $env->get('ordersPopupPosition')) ?> value="bottom-center">Inferior, centro</option>
                            <option <?php selected('bottom-right', $env->get('ordersPopupPosition')) ?> value="bottom-right">Inferior, lado direito</option>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <th>
                          <label for="">Conteúdo no popup</label>
                        </th>
                        <td>
                          <label style="gap: 5px; margin-bottom: 5px;" class="toggle-switch toggle-switch-sm" for="customerFirstName">
                            <input type="checkbox" name="ordersPopupFragments[]" value="customerFirstName" class="toggle-switch-input" id="customerFirstName" <?= checked($env->fragmentEnabled('customerFirstName')) ?>>
                            <span class="toggle-switch-label">
                              <span class="toggle-switch-indicator"></span>
                            </span>

                            Primeiro nome do cliente
                          </label>
                          <label style="gap: 5px; margin-bottom: 5px;" class="toggle-switch toggle-switch-sm" for="customerLastName">
                            <input type="checkbox" name="ordersPopupFragments[]" value="customerLastName" class="toggle-switch-input" id="customerLastName" <?= checked($env->fragmentEnabled('customerLastName')) ?>>
                            <span class="toggle-switch-label">
                              <span class="toggle-switch-indicator"></span>
                            </span>

                            Sobrenome do cliente
                          </label>
                          <label style="gap: 5px; margin-bottom: 5px;" class="toggle-switch toggle-switch-sm" for="customerLocation">
                            <input type="checkbox" name="ordersPopupFragments[]" value="customerLocation" class="toggle-switch-input" id="customerLocation" <?= checked($env->fragmentEnabled('customerLocation')) ?>>
                            <span class="toggle-switch-label">
                              <span class="toggle-switch-indicator"></span>
                            </span>

                            Localização do cliente
                          </label>
                          <label style="gap: 5px; margin-bottom: 5px;" class="toggle-switch toggle-switch-sm" for="orderDate">
                            <input type="checkbox" name="ordersPopupFragments[]" value="orderDate" class="toggle-switch-input" id="orderDate" <?= checked($env->fragmentEnabled('orderDate')) ?>>
                            <span class="toggle-switch-label">
                              <span class="toggle-switch-indicator"></span>
                            </span>

                            Data do pedido
                          </label>
                          <label style="gap: 5px; margin-bottom: 5px;" class="toggle-switch toggle-switch-sm" for="productThumbnail">
                            <input type="checkbox" name="ordersPopupFragments[]" value="productThumbnail" class="toggle-switch-input" id="productThumbnail" <?= checked($env->fragmentEnabled('productThumbnail')) ?>>
                            <span class="toggle-switch-label">
                              <span class="toggle-switch-indicator"></span>
                            </span>

                            Foto do produto
                          </label>
                          <label style="gap: 5px; margin-bottom: 5px;" class="toggle-switch toggle-switch-sm" for="userLocation">
                            <input type="checkbox" name="ordersPopupFragments[]" value="userLocation" class="toggle-switch-input" id="userLocation" <?= checked($env->fragmentEnabled('userLocation')) ?>>
                            <span class="toggle-switch-label">
                              <span class="toggle-switch-indicator"></span>
                            </span>

                            Mapa do usuário
                          </label>
                        </td>
                      </tr>

                      <tr>
                        <th>
                          <label for="excludedPages">Não exibir nas páginas</label>
                        </th>
                        <td>
                          <select name="excludedPages[]" id="excludedPages" class="select2" multiple>
                            <?php $excluded = is_array($env->get('excludedPages')) ? $env->get('excludedPages') : []; ?>
                            <?php foreach (get_pages() as $page) : ?>
                              <option value="<?= $page->ID ?>" <?= in_array($page->ID, $excluded) ? 'selected' : '' ?>>
                                <?= $page->post_title ?>
                              </option>
                              </option>
                            <?php endforeach; ?>
                          </select>
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
            <?php endif; ?>

            <div class="analytics-view full-tab-panel " id="product-visitors">
              <form method="POST" id="full-social-proof-settings" class="full-widget-form">

                <p>Este popup exibirá quantos acessos cada url teve na janela de tempo configurada</p>

                <?php wp_nonce_field('full/widget/social-proof/visitors'); ?>
                <input type="hidden" name="action" value="full/widget/social-proof/visitors">

                <table>
                  <tbody>
                    <tr>
                      <th>
                        <label for="enableRecentVisitors">Exibir em quais tipos de conteúdo?</label>
                      </th>
                      <td>
                        <?php $enabled = is_array($env->get('visitorsEnabledOn')) ? $env->get('visitorsEnabledOn') : []; ?>
                        <?php foreach (get_post_types(['public' => true], 'objects') as $cpt) : ?>
                          <label class="toggle-switch toggle-switch-sm" style="gap: 5px; margin-bottom: 5px;" for="enableRecentVisitors-<?= $cpt->name ?>">
                            <input type="checkbox" name="visitorsEnabledOn[]" value="<?= $cpt->name ?>" class="toggle-switch-input" id="enableRecentVisitors-<?= $cpt->name ?>" <?php checked(in_array($cpt->name, $enabled)) ?>>
                            <span class="toggle-switch-label">
                              <span class="toggle-switch-indicator"></span>
                            </span>
                            <?= $cpt->label ?>
                          </label>
                        <?php endforeach; ?>

                        <small>Você poderá ocultar o popup em conteúdos específicos através do editor no wp-admin.</small>
                      </td>
                    </tr>
                    <tr>
                      <th>
                        <label for="visitorTrackingWindow">Janela de tempo em minutos</label>
                      </th>
                      <td>
                        <input type="number" name="visitorTrackingWindow" id="visitorTrackingWindow" value="<?= $env->get('visitorTrackingWindow') ?>" step="1" min="0" class="custom-input">
                      </td>
                    </tr>
                    <tr>
                      <th>
                        <label for="visitorsPopupPosition">Posição do popup</label>
                      </th>
                      <td>
                        <select name="visitorsPopupPosition" id="visitorsPopupPosition" class="custom-input">
                          <option <?php selected('bottom-left', $env->get('visitorsPopupPosition')) ?> value="bottom-left">Inferior, lado esquerdo</option>
                          <option <?php selected('bottom-center', $env->get('visitorsPopupPosition')) ?> value="bottom-center">Inferior, centro</option>
                          <option <?php selected('bottom-right', $env->get('visitorsPopupPosition')) ?> value="bottom-right">Inferior, lado direito</option>
                        </select>
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

            <br>
            <br>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>