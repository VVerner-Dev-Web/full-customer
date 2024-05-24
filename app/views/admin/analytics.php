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
            <ul id="analytics-view-nav">
              <li>
                <a href="#dashboard">Relatórios</a>
              </li>
              <li><a href="#settings">Configurações</a></li>
            </ul>

            <div class="analytics-view" id="dashboard">

              <div id="chart-area">
                <div class="_header">
                  <h3>Dados</h3>

                  <div class="_header-filters">
                    <form id="data-period-form">
                      <input type="hidden" name="action" value="full/analytics/report">
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

              <div id="table-area">
                <div class="table-card">
                  <h3>Principais páginas</h3>
                  <table class="striped widefat">
                    <thead>
                      <tr>
                        <th scope="col">Caminho</th>
                        <th scope="col">Acessos</th>
                      </tr>
                    </thead>
                    <tbody id="table-pages" data-table="pages"></tbody>
                  </table>
                </div>
                <div class="table-card">
                  <h3>Principais termos em query</h3>
                  <table class="striped widefat">
                    <thead>
                      <tr>
                        <th scope="col">Query String</th>
                        <th scope="col">Acessos</th>
                      </tr>
                    </thead>
                    <tbody id="table-query-strings" data-table="queryStrings"></tbody>
                  </table>
                </div>
              </div>


            </div>

            <div class="analytics-view" id="settings">
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

          </div>
        </div>
      </div>
    </div>
  </div>
</div>