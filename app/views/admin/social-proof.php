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
                <h3>FULL.SocialProof</h3>
              </div>
            </div>
          </div>

          <div class="full-page-content">
            <ul id="analytics-view-nav">
              <?php if (defined('WC_PLUGIN_FILE')) : ?>
                <li><a href="#woocommerce">WooCommerce</a></li>
              <?php endif; ?>
            </ul>

            <?php if (defined('WC_PLUGIN_FILE')) : ?>
              <div class="analytics-view" id="woocommerce">
                <form method="POST" id="full-social-proof-settings" class="full-widget-form">
                  <?php wp_nonce_field('full/widget/social-proof'); ?>
                  <input type="hidden" name="action" value="full/widget/social-proof">

                  <table>
                    <tbody>
                      <tr>
                        <th>
                          <label for="enableWooCommerceOrdersPopup">Popups de pedidos recentes</label>
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

            <?php if (!defined('WC_PLUGIN_FILE')) : ?>
              <div class="analytics-view" style="display: block">
                <p>Você precisa ter o <strong>WooCommerce</strong> instalado para usar esta extensão</p>
              </div>
            <?php endif; ?>

            <br>
            <br>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>