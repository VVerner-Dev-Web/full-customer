<?php

use Full\Customer\ElementorCrm\Leads;

$worker = new Leads();
$forms = $worker->getForms();

?>

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
                <h3>FULL.elementor crm <abbr title="Lembre-se! Apenas formulários que já capturaram pelo menos um contato serão considerados pelo CRM."><span class="dashicons dashicons-info"></span></abbr></h3>
              </div>
            </div>
          </div>

          <div class="full-page-content">
            <form class="full-widget-form" id="full-crm" style="min-height: 500px">
              <?php if ($forms) : ?>
                <?php wp_nonce_field('full/widget/crm/form/set-stages'); ?>
                <input type="hidden" name="action" value="full/widget/crm/form/set-stages">

                <div class="form-selector">
                  <div>
                    <select name="formId" id="formId" required>
                      <option value="">Selecione um formulário</option>
                      <?php foreach ($forms as $index => $form) : ?>
                        <option value="<?php echo esc_html($index) ?>"><?php echo esc_html($form) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="lead-search">
                    <input type="text" id="lead-search" autocomplete="off" placeholder="Procurar lead">
                    <button>
                      <i class="tio-search"></i>
                    </button>
                  </div>
                </div>

                <ul id="crm-view-nav" style="display: none">
                  <li>
                    <a href="#kanban">Kanban</a>
                  </li>
                  <li><a href="#editor">Editor</a></li>
                  <li><a href="#analytics">Relatórios</a></li>
                  <li class="reload-kanban">
                    <span class="dashicons dashicons-image-rotate"></span>
                    Atualizar dados
                  </li>
                </ul>

                <div class="crm-view" id="kanban">
                  <div id="pipeline-kanban"></div>
                </div>

                <div class="crm-view" id="editor">

                  <table id="card-editor">
                    <thead>
                      <tr>
                        <th>
                          Ative os campos do formulário, para habilitar a visibilidade no card
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- JS -->
                    </tbody>
                  </table>

                  <br>

                  <table id="pipeline-editor">
                    <thead>
                      <tr>
                        <th colspan="4">
                          <div class="pipeline-editor-header">
                            Crie as etapas do seu processo de venda para organizar seu board
                            <span class="button stage-action add-stage">Adicionar estágio</span>
                          </div>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- JS -->
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>
                          <button class="full-primary-button">Enviar</button>
                        </th>
                      </tr>
                    </tfoot>
                  </table>
                </div>

                <div class="crm-view" id="analytics">
                  <p>Os leads <strong>ocultos</strong> também são classificados durante a contagem de leads capturados.</p>

                  <div class="cards-row">
                    <div class="crm-card">
                      <div class="crm-card-title">Visualizações</div>
                      <div class="crm-card-value" data-value="total_views">0</div>
                    </div>
                    <div class="crm-card">
                      <div class="crm-card-title">Total de leads</div>
                      <div class="crm-card-value" data-value="total_leads">0</div>
                    </div>
                    <div class="crm-card">
                      <div class="crm-card-title">Leads ganhos</div>
                      <div class="crm-card-value" data-value="total_won">0</div>
                    </div>
                    <div class="crm-card">
                      <div class="crm-card-title">Leads perdidos</div>
                      <div class="crm-card-value" data-value="total_lost">0</div>
                    </div>
                  </div>

                  <div class="funnel-row">
                    <div class="funnel-card-col">
                      <div class="crm-card">
                        <div class="crm-card-title">Taxa de captura</div>
                        <div class="crm-card-value" data-value="capture_rate">0</div>
                      </div>
                      <div class="crm-card">
                        <div class="crm-card-title">Taxa de conversão</div>
                        <div class="crm-card-value" data-value="conversion_rate">0</div>
                      </div>
                    </div>

                    <div class="funnel-chart-col">
                      <div id="funnel-container"></div>
                    </div>
                  </div>
                </div>

              <?php else : ?>
                <br>

                <h3>Ops! Nenhum envio encontrado</h3>
                <p>Para usar o CRM, primeiro você precisa capturar contatos através dos formulários criados com o Elementor PRO. Após isso, você poderá criar funis de venda personalizados por cada formulário em seu site!</p>
              <?php endif; ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text-template" id="stage-template">
  <tr>
    <td class="stage-cell">
      <div class="stage-editions">
        <input class="" type="text" placeholder="Nome do estágio">
        <select>
          <option value="">Não finalizado</option>
          <option value="won">Marcar como ganho</option>
          <option value="lost">Marcar como perdido</option>
        </select>
        <div class="actions">
          <a href="#" tabindex="-1" class="stage-action up-stage">Subir</a>
          <a href="#" tabindex="-1" class="stage-action remove-stage">Remover</a>
          <a href="#" tabindex="-1" class="stage-action down-stage">Descer</a>
        </div>
      </div>
    </td>
  </tr>
</script>

<script type="text-template" id="card-fragment-editor-template">
  <tr>
    <td>
      <div class="fragment-container">
        <label for="" class="fragment-toggle">
          <input type="checkbox" name="fragments[]" value="">
          <span class="fragment-indicator"></span>
        </label>
        <span class="fragment-name"></span>
      </div>
    </td>
  </tr>
</script>

<script type="text-template" id="kanban-card-template">
  <a class="kanban-item" target="_blank" rel="noopener noreferrer">
    <div class="kanban-item-fragments"></div>
    <div class="kanban-item-footer">
      <span class="view-lead">Ver mais</span>

      <span data-action="hide" class="hide-lead" title="Ao ocultar um item, você poderá consultá-lo novamente na listagem padrão de envios do Elementor PRO">Ocultar</span>
      <span data-action="delete" class="delete-lead" title="Esta ação não poderá ser desfeita">Excluir</span>
    </div>
  </a>
</script>

<script type="text-template" id="card-fragment-template">
  <div class="kanban-item-content">
    <small class="kanban-item-key"></small>
    <strong class="kanban-item-value"></strong>
  </div>
</script>

<script type="text-template" id="kanban-column-template">
  <div class="kanban-column">
    <div class="kanban-column-header">
      <div class="kanban-column-title"></div>
    </div>
    <div class="kanban-column-items"></div>
  </div>
</script>

<script type="text-template" id="funnel-segment">
  <div class="funnel-segment">
    <div class="funnel-segment-title"></div>
    <div class="funnel-segment-value"></div>
  </div>
</script>