<div class="full-templates-admin-body">
  <div class="templately-wrapper">
    <div class="templately-header">
      <div class="templately-logo">
        <img src="<?php echo esc_url(fullGetImageUrl('logo-novo.png')) ?>" alt="Logo FULL">
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
            <?php $worker = new Full\Customer\WooCommerce\Settings(); ?>
            <?php if (!$worker->hasWooCommerce()) : ?>

              <p>📢 Você precisa ter o WooCommerce instalado para usar esta extensão</p>

            <?php else : ?>

              <ul id="analytics-view-nav" class="full-tab-nav">
                <li><a href="#optimization">Otimização geral</a></li>
                <li><a href="#secret-coupon">Cupom secreto</a></li>
                <li><a href="#checkout-redirect">Redirecionamento após a compra</a></li>
              </ul>

              <div class="full-tab-panel analytics-view" id="optimization">
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
                          <small>Para editar o nome e descrição do gateway, <a href="<?php echo esc_url(admin_url('admin.php?page=wc-settings&tab=checkout&section=full-customer')) ?>" target="_blank" rel="noopener noreferrer">clique aqui</a></small>
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

                      <tr class="whatsapp-checkout <?php echo $worker->get('enableWhatsAppCheckout') ? '' : 'hidden' ?>">
                        <th>
                          <label for="whatsAppCheckoutNumber">Número para receber pedidos</label>
                        </th>
                        <td>
                          <input type="text" name="whatsAppCheckoutNumber" placeholder="(00) 987.564.231" value="<?php echo $worker->get('whatsAppCheckoutNumber') ?>" class="custom-input" id="whatsAppCheckoutNumber" <?php echo $worker->get('enableWhatsAppCheckout') ? 'required' : '' ?>>
                        </td>
                      </tr>

                      <tr class="whatsapp-checkout <?php echo $worker->get('enableWhatsAppCheckout') ? '' : 'hidden' ?>">
                        <th>
                          <label for="whatsAppCheckoutMessage">Mensagem padrão</label><br>
                        </th>
                        <td>
                          <textarea class="custom-input" style="min-height: 150px" name="whatsAppCheckoutMessage" id="whatsAppCheckoutMessage" cols="30" rows="10" <?php echo $worker->get('enableWhatsAppCheckout') ? 'required' : '' ?>><?php echo $worker->get('whatsAppCheckoutMessage') ?></textarea>
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
                          <small>Para editar o nome e descrição do gateway, <a href="<?php echo esc_url(admin_url('admin.php?page=wc-settings&tab=checkout&section=full-customer-estimate')) ?>" target="_blank" rel="noopener noreferrer">clique aqui</a></small>
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

                      <tr class="custom-tab <?php echo $worker->get('enableProductCustomTab') ? '' : 'hidden' ?>">
                        <th>
                          <label for="customProductTabName">Nome da aba</label>
                        </th>
                        <td>
                          <input type="text" name="customProductTabName" d="customProductTabName" value="<?php echo $worker->get('customProductTabName') ?>" class="custom-input">
                        </td>
                      </tr>

                      <tr class="custom-tab <?php echo $worker->get('enableProductCustomTab') ? '' : 'hidden' ?>">
                        <th>
                          <label for="customProductTabContent">Conteúdo da aba</label>
                        </th>
                        <td>
                          <textarea name="customProductTabContent" id="customProductTabContent" cols="30" rows="10" class="custom-input" style="min-height: 150px"><?php echo $worker->get('customProductTabContent') ?></textarea>
                          <small>Para usuários avançados: Utilize o hook do_action('full-customer/woocommerce/custom-product-tab-content') para inserir conteúdos</small>
                        </td>
                      </tr>

                      <tr>
                        <th>
                          <label for="orderReceivedPageCustomCode">Código para tela de obrigado</label>
                        </th>
                        <td>
                          <textarea class="codemirror-code-value hidden" name="orderReceivedPageCustomCode"><?php echo $worker->get('orderReceivedPageCustomCode') ?></textarea>
                          <textarea class="codemirror-code" data-mode="htmlmixed"><?php echo $worker->get('orderReceivedPageCustomCode') ?></textarea>
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

              <div class="full-tab-panel analytics-view" id="secret-coupon">
                <?php $worker = new Full\Customer\SecretCoupon\Settings(); ?>
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
                          <input type="text" name="warningMessage" id="warningMessage" value="<?php echo $worker->get('warningMessage') ?>" class="custom-input">
                          <small>Utilize {valorMinimo}, {valorFaltante} como campos dinâmicos</small>
                        </td>
                      </tr>

                      <tr>
                        <th>
                          <label for="appliedMessage">Mensagem de cupom desbloqueado</label>
                        </th>
                        <td>
                          <input type="text" name="appliedMessage" id="appliedMessage" value="<?php echo $worker->get('appliedMessage') ?>" class="custom-input">
                        </td>
                      </tr>

                      <tr>
                        <th>
                          <label for="minimumAmount">Valor mínimo do carrinho</label>
                        </th>
                        <td>
                          <input type="number" min="0" name="minimumAmount" id="minimumAmount" value="<?php echo $worker->get('minimumAmount') ?>" class="custom-input">
                        </td>
                      </tr>

                      <tr>
                        <th>
                          <label for="couponAmount">Desconto</label>
                        </th>
                        <td>
                          <input type="text" name="couponAmount" id="couponAmount" value="<?php echo $worker->get('couponAmount') ?>" class="custom-input">
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
              </div>

              <div class="full-tab-panel analytics-view" id="checkout-redirect">
                <?php $worker = new Full\Customer\CheckoutRedirect\Settings(); ?>

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
                          <input type="url" name="successRedirect" id="successRedirect" value="<?php echo $worker->get('successRedirect') ?>" class="custom-input">
                          <small>Pedidos com status: Processando ou Concluído</small>
                        </td>
                      </tr>

                      <tr>
                        <th>
                          <label for="errorRedirect">Pagamentos com erro</label>
                        </th>
                        <td>
                          <input type="url" name="errorRedirect" id="errorRedirect" value="<?php echo $worker->get('errorRedirect') ?>" class="custom-input">
                          <small>Pedidos com status: Malsucedido, Cancelado ou Reembolsado</small>
                        </td>
                      </tr>

                      <tr>
                        <th>
                          <label for="pendingRedirect">Pagamentos pendente</label>
                        </th>
                        <td>
                          <input type="url" name="pendingRedirect" id="pendingRedirect" value="<?php echo $worker->get('pendingRedirect') ?>" class="custom-input">
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
              </div>

            <?php endif; ?>


          </div>
        </div>
      </div>
    </div>
  </div>
</div>