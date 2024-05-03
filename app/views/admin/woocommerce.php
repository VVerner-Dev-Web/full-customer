<?php

use Full\Customer\WooCommerce\Settings;

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
                <h3>FULL.woocommerce</h3>
              </div>
            </div>
          </div>

          <div class="full-page-content">

            <?php if (!$worker->hasWooCommerce()) : ?>

              <p>📢 Você precisa ter o WooCommerce instalado para usar esta extensão</p>

            <?php else : ?>

              <form method="POST" id="full-woocommerce-settings" class="full-widget-form" style="margin-bottom: 30px">
                <?php wp_nonce_field('full/widget/woocommerce-settings'); ?>
                <input type="hidden" name="action" value="full/widget/woocommerce-settings">

                <table>
                  <tbody>
                    <tr>
                      <th>
                        <label for="enableTestPaymentGateway">Gateway de pagamento teste para admins?</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="enableTestPaymentGateway">
                          <input type="checkbox" name="enableTestPaymentGateway" value="1" class="toggle-switch-input" id="enableTestPaymentGateway" <?php checked($worker->get('enableTestPaymentGateway')) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                        </label>
                        <small>Para editar o nome e descrição do gateway, <a href="<?= admin_url('admin.php?page=wc-settings&tab=checkout&section=full-customer') ?>" target="_blank" rel="noopener noreferrer">clique aqui</a></small>
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="enableWhatsAppCheckout">Ativar finalização de compra por WhatsApp?</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="enableWhatsAppCheckout">
                          <input type="checkbox" name="enableWhatsAppCheckout" value="1" class="toggle-switch-input" id="enableWhatsAppCheckout" <?php checked($worker->get('enableWhatsAppCheckout')) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                        </label>
                      </td>
                    </tr>

                    <tr class="whatsapp-checkout <?= $worker->get('enableWhatsAppCheckout') ? '' : 'hidden' ?>">
                      <th>
                        <label for="whatsAppCheckoutNumber">Número para receber pedidos</label>
                      </th>
                      <td>
                        <input type="text" name="whatsAppCheckoutNumber" placeholder="(00) 987.564.231" value="<?= $worker->get('whatsAppCheckoutNumber') ?>" class="custom-input" id="whatsAppCheckoutNumber" <?= $worker->get('enableWhatsAppCheckout') ? 'required' : '' ?>>
                      </td>
                    </tr>

                    <tr class="whatsapp-checkout <?= $worker->get('enableWhatsAppCheckout') ? '' : 'hidden' ?>">
                      <th>
                        <label for="whatsAppCheckoutMessage">Mensagem padrão</label><br>
                      </th>
                      <td>
                        <textarea class="custom-input" style="min-height: 150px" name="whatsAppCheckoutMessage" id="whatsAppCheckoutMessage" cols="30" rows="10" <?= $worker->get('enableWhatsAppCheckout') ? 'required' : '' ?>><?= $worker->get('whatsAppCheckoutMessage') ?></textarea>
                        <p style="margin-bottom: 0"><strong>Campos dinâmicos:</strong> {itens_do_carrinho}, {preco_total_carrinho}</p>
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="enableEstimateOrders">Opção de solicitar orçamento no checkout?</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="enableEstimateOrders">
                          <input type="checkbox" name="enableEstimateOrders" value="1" class="toggle-switch-input" id="enableEstimateOrders" <?php checked($worker->get('enableEstimateOrders')) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                        </label>
                        <small>Para editar o nome e descrição do gateway, <a href="<?= admin_url('admin.php?page=wc-settings&tab=checkout&section=full-customer-estimate') ?>" target="_blank" rel="noopener noreferrer">clique aqui</a></small>
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="hidePrices">Ocultar preços na página de produto e loja</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="hidePrices">
                          <input type="checkbox" name="hidePrices" value="1" class="toggle-switch-input" id="hidePrices" <?php checked($worker->get('hidePrices')) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                        </label>
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="autocompleteProcessingOrders">Mover pedidos com status "processando" para "concluído" automaticamente</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="autocompleteProcessingOrders">
                          <input type="checkbox" name="autocompleteProcessingOrders" value="1" class="toggle-switch-input" id="autocompleteProcessingOrders" <?php checked($worker->get('autocompleteProcessingOrders')) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                        </label>
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="disableProductReviews">Desativar avaliações de produtos</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="disableProductReviews">
                          <input type="checkbox" name="disableProductReviews" value="1" class="toggle-switch-input" id="disableProductReviews" <?php checked($worker->get('disableProductReviews')) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                        </label>
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="enableProductCustomTab">Ativar aba personalizada nos produtos</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="enableProductCustomTab">
                          <input type="checkbox" name="enableProductCustomTab" value="1" class="toggle-switch-input" id="enableProductCustomTab" <?php checked($worker->get('enableProductCustomTab')) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                        </label>
                      </td>
                    </tr>

                    <tr class="custom-tab <?= $worker->get('enableProductCustomTab') ? '' : 'hidden' ?>">
                      <th>
                        <label for="customProductTabName">Nome da aba</label>
                      </th>
                      <td>
                        <input type="text" name="customProductTabName" d="customProductTabName" value="<?= $worker->get('customProductTabName') ?>" class="custom-input">
                      </td>
                    </tr>

                    <tr class="custom-tab <?= $worker->get('enableProductCustomTab') ? '' : 'hidden' ?>">
                      <th>
                        <label for="customProductTabContent">Conteúdo da aba</label>
                      </th>
                      <td>
                        <textarea name="customProductTabContent" id="customProductTabContent" cols="30" rows="10" class="custom-input" style="min-height: 150px"><?= $worker->get('customProductTabContent') ?></textarea>
                        <small>Para usuários avançados: Utilize o hook do_action('full-customer/woocommerce/custom-product-tab-content') para inserir conteúdos</small>
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="orderReceivedPageCustomCode">Código para tela de obrigado</label>
                      </th>
                      <td>
                        <textarea class="codemirror-code-value hidden" name="orderReceivedPageCustomCode"><?= $worker->get('orderReceivedPageCustomCode') ?></textarea>
                        <textarea class="codemirror-code" data-mode="htmlmixed"><?= $worker->get('orderReceivedPageCustomCode') ?></textarea>
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