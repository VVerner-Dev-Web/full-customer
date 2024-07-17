<?php

use Full\Customer\CheckoutRedirect\Settings;

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
                <h3>FULL.Checkout Redirect</h3>
              </div>
            </div>
          </div>

          <div class="full-page-content">

            <?php if (!defined('WC_PLUGIN_FILE')) : ?>
              <div class="analytics-view" style="display: block">
                <p>Você precisa ter o <strong>WooCommerce</strong> instalado para usar esta extensão</p>
              </div>
            <?php else : ?>
              <form method="POST" id="full-checkout-redirect-settings" class="full-widget-form" style="margin-bottom: 30px">
                <?php wp_nonce_field('full/widget/checkout-redirect'); ?>
                <input type="hidden" name="action" value="full/widget/checkout-redirect">

                <br>

                <p>A configuração abaixo será aplicado independente do produto adquirido pelo cliente, se você deseja redirecionar para uma página específica com base no produto adquirido, realize a configuração nas opções do produto.</p>

                <table>
                  <tbody>

                    <tr>
                      <th>
                        <label for="successRedirect">Pagamentos bem sucedidos</label>
                      </th>
                      <td>
                        <input type="url" name="successRedirect" id="successRedirect" value="<?= $worker->get('successRedirect') ?>" class="custom-input">
                        <small>Pedidos com status: Processando ou Concluído</small>
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="errorRedirect">Pagamentos com erro</label>
                      </th>
                      <td>
                        <input type="url" name="errorRedirect" id="errorRedirect" value="<?= $worker->get('errorRedirect') ?>" class="custom-input">
                        <small>Pedidos com status: Malsucedido, Cancelado ou Reembolsado</small>
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="pendingRedirect">Pagamentos pendente</label>
                      </th>
                      <td>
                        <input type="url" name="pendingRedirect" id="pendingRedirect" value="<?= $worker->get('pendingRedirect') ?>" class="custom-input">
                        <small>Pedidos com status: Aguardando ou Pagamento pendente</small>
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
