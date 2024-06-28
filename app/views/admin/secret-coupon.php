<?php

use Full\Customer\SecretCoupon\Settings;

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
                <h3>FULL.Cupom Secreto</h3>
              </div>
            </div>
          </div>

          <div class="full-page-content">

            <?php if (!defined('WC_PLUGIN_FILE')) : ?>
              <div class="analytics-view" style="display: block">
                <p>Você precisa ter o <strong>WooCommerce</strong> instalado para usar esta extensão</p>
              </div>
            <?php else : ?>
              <form method="POST" id="full-secret-coupon-settings" class="full-widget-form" style="margin-bottom: 30px">
                <?php wp_nonce_field('full/widget/secret-coupon'); ?>
                <input type="hidden" name="action" value="full/widget/secret-coupon">

                <p>Para exibição do alerta sobre o cupom secreto o WooCommerce clássico - via shortcodes - deve estar ativo em seu site.</p>

                <table>
                  <tbody>

                    <tr>
                      <th>
                        <label for="enabled">Ativar cupom secreto?</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="enabled">
                          <input type="checkbox" name="enabled" value="1" class="toggle-switch-input" id="enabled" <?php checked($worker->get('enabled')) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                        </label>
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="warningMessage">Mensagem de aviso</label>
                      </th>
                      <td>
                        <input type="text" name="warningMessage" id="warningMessage" value="<?= $worker->get('warningMessage') ?>" class="custom-input">
                        <small>Utilize {valorMinimo}, {valorFaltante} como campos dinâmicos</small>
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="appliedMessage">Mensagem de cupom desbloqueado</label>
                      </th>
                      <td>
                        <input type="text" name="appliedMessage" id="appliedMessage" value="<?= $worker->get('appliedMessage') ?>" class="custom-input">
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="minimumAmount">Valor mínimo do carrinho</label>
                      </th>
                      <td>
                        <input type="number" min="0" name="minimumAmount" id="minimumAmount" value="<?= $worker->get('minimumAmount') ?>" class="custom-input">
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="couponAmount">Desconto</label>
                      </th>
                      <td>
                        <input type="text" name="couponAmount" id="couponAmount" value="<?= $worker->get('couponAmount') ?>" class="custom-input">
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="discountType">Tipo de desconto</label>
                      </th>
                      <td>
                        <select name="discountType" id="discountType" class="custom-input">
                          <option value="fixed_cart" <?php selected('fixed_cart', $worker->get('discountType')) ?>>Fixo</option>
                          <option value="percent" <?php selected('percent', $worker->get('discountType')) ?>>Porcentagem</option>
                        </select>
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="discountAmount">Habilita frete grátis?</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="freeShipping">
                          <input type="checkbox" name="freeShipping" value="1" class="toggle-switch-input" id="freeShipping" <?php checked($worker->get('freeShipping')) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                        </label>
                        <small>Lembre-se de configurar um método de entrega do tipo "Frete Grátis" para funcionar</small>
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
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>