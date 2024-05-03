<?php

use Full\Customer\Security\Settings;

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
                <h3>FULL.firewall</h3>
              </div>
            </div>
          </div>

          <div class="full-page-content">
            <p>Ao ativar a extensão FULL.firewall, seu site fica automaticamente equipado com um firewall rigoroso contra as principais ameaças a sites WordPress.</p>

            <form method="POST" id="full-security-settings" class="full-widget-form" style="margin-bottom: 30px">
              <?php wp_nonce_field('full/widget/security-settings'); ?>
              <input type="hidden" name="action" value="full/widget/security-settings">

              <table>
                <tbody>
                  <tr>
                    <th>
                      <label for="enableLastLoginColumn">Adicionar coluna com última data de login</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="enableLastLoginColumn">
                        <input type="checkbox" name="enableLastLoginColumn" value="1" class="toggle-switch-input" id="enableLastLoginColumn" <?php checked($worker->get('enableLastLoginColumn')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>

                  <tr>
                    <th>
                      <label for="disableFeeds">Desabilitar Feeds de conteúdo</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="disableFeeds">
                        <input type="checkbox" name="disableFeeds" value="1" class="toggle-switch-input" id="disableFeeds" <?php checked($worker->get('disableFeeds')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>

                  <tr>
                    <th>
                      <label for="disableXmlrpc">Desabilitar XML-RPC</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="disableXmlrpc">
                        <input type="checkbox" name="disableXmlrpc" value="1" class="toggle-switch-input" id="disableXmlrpc" <?php checked($worker->get('disableXmlrpc')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>

                  <tr>
                    <th>
                      <label for="enablePasswordProtection">Proteger site com senha</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="enablePasswordProtection">
                        <input type="checkbox" name="enablePasswordProtection" value="1" class="toggle-switch-input" id="enablePasswordProtection" <?php checked($worker->get('enablePasswordProtection')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
                      </label>
                    </td>
                  </tr>

                  <tr class="password <?= $worker->get('enablePasswordProtection') ? '' : 'hidden' ?>">
                    <th>
                      <label for="sitePassword">Senha de acesso</label>
                    </th>
                    <td>
                      <input type="password" name="sitePassword" id="sitePassword" value="<?= $worker->get('sitePassword') ?>" class="custom-input">
                    </td>
                  </tr>

                  <tr>
                    <th>
                      <label for="enableUsersOnlyMethod">Permitir acesso apenas para usuários autenticados</label>
                    </th>
                    <td>
                      <label class="toggle-switch toggle-switch-sm" for="enableUsersOnlyMethod">
                        <input type="checkbox" name="enableUsersOnlyMethod" value="1" class="toggle-switch-input" id="enableUsersOnlyMethod" <?php checked($worker->get('enableUsersOnlyMethod')) ?>>
                        <span class="toggle-switch-label">
                          <span class="toggle-switch-indicator"></span>
                        </span>
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

            <?php if (current_user_can('manage_options')) :
              global $wp_version;
              $server_software_raw = str_replace("/", " ", $_SERVER['SERVER_SOFTWARE']);
              $server_software_parts = explode(" (", $server_software_raw);
              $server_software = ucfirst($server_software_parts[0]);

              if (mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)) {
                $db_full_version = mysqli_get_server_info(mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME));
                $db_version_parts = explode(':', $db_full_version);
                $db_short_version = $db_version_parts[0];
                $db_separator = ' | ';
              } else {
                $db_short_version = '';
                $db_separator = '';
              }
            ?>

              <h3>Dados do sistema</h3>
              <form class="full-widget-form" style="margin-bottom: 30px">

                <table>
                  <tbody>
                    <tr>
                      <th>Servidor</th>
                      <td><?= $server_software ?></td>
                    </tr>
                    <tr>
                      <th>PHP</th>
                      <td><?= phpversion(); ?> (<?= php_sapi_name() ?>)</td>
                    </tr>
                    <tr>
                      <th>Banco de dados</th>
                      <td><?= $db_short_version !== '' && $db_short_version !== '0' ? $db_short_version : 'Não reconhecido' ?></td>
                    </tr>
                    <tr>
                      <th>Versão do WordPress</th>
                      <td><?= $wp_version ?></td>
                    </tr>
                    <tr>
                      <th>Em modo de depuração? (WP_DEBUG)</th>
                      <td><?= defined('WP_DEBUG') && WP_DEBUG ? 'Sim' : 'Não' ?></td>
                    </tr>
                    <tr>
                      <th>Versão da FULL</th>
                      <td><?= FULL_CUSTOMER_VERSION ?></td>
                    </tr>
                  </tbody>
                </table>

              <?php endif; ?>
              </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>