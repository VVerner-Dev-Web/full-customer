<?php

use Full\Customer\Analytics\Settings;

$env = new Settings;
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
                <h3>FULL.analytics</h3>
              </div>
            </div>
          </div>

          <div class="full-page-content">
            <ul id="analytics-view-nav" class="full-tab-nav">
              <li><a href="#dashboard">Relatórios</a></li>
              <li><a href="#journeys">Jornadas</a></li>
              <li><a href="#conversions">Conversões</a></li>
              <li><a href="#settings">Configurações</a></li>
            </ul>

            <div class="full-tab-panel analytics-view" id="dashboard">

              <div id="chart-area">
                <div class="_header">
                  <h3>Session Tracking</h3>

                  <div class="_header-filters">
                    <form id="data-period-form">
                      <input type="hidden" name="action" value="full/analytics/report">
                      <input type="hidden" name="journey" value="0">
                      <?php wp_nonce_field('full/analytics/report') ?>
                      <input type="text" name="period" id="dataPeriod" value="<?= date('d/m/Y', strtotime('-6 days')) . ' - ' .  current_time('d/m/Y') ?>">
                    </form>
                  </div>
                </div>

                <div class="_body">
                  <canvas id="dashboard-chart"></canvas>

                  <div id="dashboard-chart-legend">
                    <div class="small-card">
                      <div class="small-card-body">
                        <strong>Seções</strong>
                        <h4 class="totals-sessions"></h4>
                      </div>
                    </div>
                    <div class="small-card">
                      <div class="small-card-body">
                        <strong>Páginas</strong>
                        <h4 class="totals-views"></h4>
                      </div>
                    </div>
                    <div class="small-card">
                      <div class="small-card-body">
                        <strong>Páginas por seção</strong>
                        <h4 class="totals-average"></h4>
                      </div>
                    </div>
                    <div class="small-card">
                      <div class="small-card-body">
                        <strong>Mais acessada</strong>
                        <h4 class="top-page"></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <br>
              <br>
              <br>
              <br>

              <div id="table-area">
                <div class="table-card" style="max-width: 100%;">
                  <h3>Top Pages</h3>
                  <table class="striped widefat">
                    <thead>
                      <tr>
                        <th scope="col">Página Acessada</th>
                        <th scope="col">Acessos</th>
                      </tr>
                    </thead>
                    <tbody id="table-pages" data-table="pages"></tbody>
                  </table>
                </div>
              </div>

              <br>
              <br>
              <br>
              <br>

              <div id="journey-view">
                <div class="_header">
                  <h3 for="chartJourney">Jornadas</h3>
                  <select id="chartJourney" class="show-for-journeys"></select>
                </div>

                <div id="journey-stats" class="show-for-journeys">
                  <div class="table-card">
                    <canvas class="show-for-journeys" id="current-journey-chart"></canvas>
                  </div>
                  <div class="table-card">
                    <h3>Funnel Tracking</h3>
                    <table class="striped widefat">
                      <thead>
                        <tr>
                          <th scope="col">Página Acessada</th>
                          <th scope="col">
                            <abbr title="% de usuários que acessaram a página anterior na jornada e transitaram diretamente para esta página."><span class="dashicons dashicons-info"></span></abbr>
                            Taxa de Transição
                          </th>
                          <th scope="col">
                            <abbr title="% de usuários que seguiram a jornada completa até chegar a esta página."><span class="dashicons dashicons-info"></span></abbr>
                            Taxa de Conversão da Jornada
                          </th>
                        </tr>
                      </thead>
                      <tbody id="journey-rate"></tbody>
                    </table>
                  </div>
                </div>

                <p class="hide-for-journeys" style="display: none">Crie ou selecione uma jornada para visualizar as estatísticas</p>
              </div>
            </div>

            <div class="full-tab-panel analytics-view" id="journeys">
              <h3>Jornada dos visitantes</h3>
              <p>Crie múltiplas jornadas de usuários ao site e acompanhe a navegação deles em seu site</p>

              <table id="current-journeys" class="widefat striped show-for-journeys" style="margin: 0; border: unset;">
                <thead>
                  <tr>
                    <th scope="col">Nome da jornada</th>
                    <th scope="col">Etapas</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>

              <br>

              <button data-modal="#modal-journey-editor" class="full-primary-button">Criar jornada</button>
            </div>

            <div class="full-tab-panel analytics-view" id="conversions">
              <h3>Conversões</h3>
              <p>Defina eventos de conversão em seu site e acompanhe a evolução deles</p>

              <table id="current-conversions" class="widefat striped show-for-journeys" style="margin: 0; border: unset;">
                <thead>
                  <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Tipo</th>
                    <th style="text-align: center" scope="col"><abbr title="Índice de conversão entre total de seções X total de eventos em todo o período">Conversão</abbr></th>
                    <th style="text-align: center" scope="col"><abbr title="Contagem de eventos que aconteceram no período indicado comparado ao período anterior">7 dias</abbr></th>
                    <th style="text-align: center" scope="col"><abbr title="Contagem de eventos que aconteceram no período indicado comparado ao período anterior">30 dias</abbr></th>
                    <th style="text-align: center" scope="col"><abbr title="Contagem de eventos que aconteceram no período indicado comparado ao período anterior">90 dias</abbr></th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>

              <br>

              <button data-modal="#modal-conversion-editor" class="full-primary-button">Criar conversão</button>
            </div>

            <div class="full-tab-panel analytics-view" id="settings">
              <form class="full-widget-form" id="full-analytics-settings" style="min-height: 500px">
                <?php wp_nonce_field('full/analytics/settings'); ?>
                <input type="hidden" name="action" value="full/analytics/settings">

                <table>
                  <tbody>
                    <tr>
                      <th><label for="trackingUsers">Acompanhar usuários logados?</label></th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="trackingUsers">
                          <input type="checkbox" name="trackingUsers" value="1" class="toggle-switch-input" id="trackingUsers" <?= checked($env->get('trackingUsers')) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                        </label>
                      </td>
                    </tr>
                    <tr>
                      <th><label for="trackingPeriod">Reter dados por quantos dias?</label></th>
                      <td>
                        <input type="number" name="trackingPeriod" id="trackingPeriod" value="<?= $env->get('trackingPeriod') ?>" min="0" step="1"><br>
                        <small>Deixe em branco para não excluir dados antigos</small>
                      </td>
                    </tr>
                    <tr>
                      <td><button class="full-primary-button">Atualizar</button></td>
                      <td></td>
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

<div class="full-modal-container" id="modal-journey-editor">
  <div class="full-modal-overlay"></div>
  <div class="full-modal">
    <div class="full-modal-header">
      <h3>Nova jornada</h3>
      <a href="#" class="full-modal-close">&times;</a>
    </div>
    <div class="full-modal-body">
      <form class="full-widget-form" id="full-analytics-journey">
        <?php wp_nonce_field('full/analytics/journey'); ?>
        <input type="hidden" name="action" value="full/analytics/journey">
        <input type="hidden" name="journeyId" id="journeyId" value="">

        <label for="journeyName">Nome da jornada</label>
        <input type="text" name="journeyName" id="journeyName" class="large-text" required>

        <table id="pipeline-editor">
          <thead>
            <tr>
              <td colspan="4">
                <div class="pipeline-editor-header">
                  Defina as URLs da jornada do cliente.
                  <span class="button stage-action add-stage">Adicionar url</span>
                </div>
              </td>
            </tr>
          </thead>
          <tbody>
            <!-- JS -->
          </tbody>
          <tfoot>
            <tr>
              <th>
                <button class="full-primary-button">Salvar</button>
              </th>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>

<div class="full-modal-container" id="modal-conversion-editor">
  <div class="full-modal-overlay"></div>
  <div class="full-modal">
    <div class="full-modal-header">
      <h3>Nova conversão</h3>
      <a href="#" class="full-modal-close">&times;</a>
    </div>
    <div class="full-modal-body">
      <form class="full-widget-form" id="full-analytics-journey">
        <?php wp_nonce_field('full/analytics/conversion'); ?>
        <input type="hidden" name="action" value="full/analytics/conversion">
        <input type="hidden" name="conversionId" id="conversionId" value="">

        <div style="margin-bottom: 15px">
          <label for="conversionName">Nome da conversão</label>
          <input type="text" name="conversionName" id="conversionName" class="large-text" required>
        </div>

        <div style="margin-bottom: 15px">
          <label for="conversionType">Tipo de conversão</label>
          <select name="conversionType" id="conversionType" style="display: block; width: 100%; max-width: unset;" required>
            <option value="element:click">Clique em elemento</option>
            <option value="element:submit">Envio de formulário</option>
            <option value="page:view">Acesso a página</option>
          </select>
        </div>

        <div style="margin-bottom: 15px">
          <label for="conversionElement">Localizador</label>
          <input type="text" name="conversionElement" id="conversionElement" class="large-text" required>
          <span class="conversion-tutorial for-click" style="display: none">Insira o seletor CSS do botão <a href="<?= fullGetImageUrl('analytics/tutorial-botao.webp') ?>" target="_blank" rel="noopener noreferrer">Ver exemplo</a></span>
          <span class="conversion-tutorial for-submit" style="display: none">Insira o seletor CSS do formulário <a href="<?= fullGetImageUrl('analytics/tutorial-form.webp') ?>" target="_blank" rel="noopener noreferrer">Ver exemplo</a></span>
          <span class="conversion-tutorial for-view" style="display: none">Insira a URL que o visitante deve acessar <a href="<?= fullGetImageUrl('analytics/tutorial-link.webp') ?>" target="_blank" rel="noopener noreferrer">Ver exemplo</a></span>
        </div>

        <button class="full-primary-button">Salvar</button>
      </form>
    </div>
  </div>
</div>

<template id="journey-stage-row">
  <tr>
    <td class="stage-cell">
      <div class="stage-editions">
        <input name="journey[]" type="url" value="" placeholder="URL do site">
        <div class="actions">
          <a href="#" tabindex="-1" class="stage-action up-stage">Subir</a>
          <a href="#" tabindex="-1" class="stage-action remove-stage">Remover</a>
          <a href="#" tabindex="-1" class="stage-action down-stage">Descer</a>
        </div>
      </div>
    </td>
  </tr>
</template>

<template id="existing-journey-row">
  <tr>
    <td class="journey-name">Nome da jornada</td>
    <td class="journey-stages">Etapas</td>
    <td>
      <button class="full-secondary-button  journey-view">Ver relatório</button>
      <button class="full-secondary-button  journey-edit">Editar</button>
      <button class="full-secondary-button  journey-delete">Excluir</button>
    </td>
  </tr>
</template>

<template id="existing-conversion-row">
  <tr>
    <td class="conversion-name">Nome da jornada</td>
    <td class="conversion-type">Etapas</td>
    <td style="text-align: center" class="conversion-global-rate">30%</td>
    <td>
      <span class="conversion-data" data-period="7days">
        <span class="current">3</span>
        <span class="change ">+2</span>
      </span>
    </td>
    <td>
      <span class="conversion-data" data-period="30days">
        <span class="current">100</span>
        <span class="change ">+50</span>
      </span>
    </td>
    <td>
      <span class="conversion-data" data-period="90days">
        <span class="current">1000</span>
        <span class="change ">-500</span>
      </span>
    </td>
    <td style="text-align: right">
      <button class="full-secondary-button conversion-edit">Editar</button>
      <button class="full-secondary-button conversion-delete">Excluir</button>
    </td>
  </tr>
</template>