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
                <h3>FULL.config</h3>
              </div>
            </div>
          </div>

          <div class="full-page-content">

            <ul id="analytics-view-nav" class="full-tab-nav">
              <li><a href="#powerups">PowerUps</a></li>
              <li><a href="#optimization">Otimização</a></li>
              <li><a href="#custom-code">Código personalizado</a></li>
              <li><a href="#wp-admin-custom">Personalização do wp-admin</a></li>
            </ul>

            <div class="full-tab-panel analytics-view" id="powerups">
              <?php $worker = new Full\Customer\Seo\Settings(); ?>
              <form method="POST" id="full-content-settings" class="full-widget-form">
                <?php wp_nonce_field('full/widget/content-settings'); ?>
                <input type="hidden" name="action" value="full/widget/content-settings">

                <table>
                  <tbody>

                    <tr>
                      <th>
                        <label for="enableContentDuplication">Duplicação conteúdo</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="enableContentDuplication">
                          <input type="checkbox" name="enableContentDuplication" value="1" class="toggle-switch-input" id="enableContentDuplication" <?php checked($worker->get('enableContentDuplication')) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                        </label>
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="disableComments">Desativar comentários em todo o site</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="disableComments">
                          <input type="checkbox" name="disableComments" value="1" class="toggle-switch-input" id="disableComments" <?php checked($worker->get('disableComments')) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                        </label>
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="redirect404ToHomepage">Redirecionar erros 404 para a página inicial</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="redirect404ToHomepage">
                          <input type="checkbox" name="redirect404ToHomepage" value="1" class="toggle-switch-input" id="redirect404ToHomepage" <?php checked($worker->get('redirect404ToHomepage')) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                        </label>
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="openExternalLinkInNewTab">Abrir links de outros sites em nova guia</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="openExternalLinkInNewTab">
                          <input type="checkbox" name="openExternalLinkInNewTab" value="1" class="toggle-switch-input" id="openExternalLinkInNewTab" <?php checked($worker->get('openExternalLinkInNewTab')) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                        </label>
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="publishMissingSchedulePosts">Publicar automaticamente posts que perderam agendamento</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="publishMissingSchedulePosts">
                          <input type="checkbox" name="publishMissingSchedulePosts" value="1" class="toggle-switch-input" id="publishMissingSchedulePosts" <?php checked($worker->get('publishMissingSchedulePosts')) ?>>
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
            </div>

            <div class="full-tab-panel analytics-view" id="optimization">

              <?php $worker = new Full\Customer\Speed\Settings(); ?>
              <form method="POST" id="full-speed-settings" class="full-widget-form" style="margin-bottom: 30px">
                <?php wp_nonce_field('full/widget/speed-settings'); ?>
                <input type="hidden" name="action" value="full/widget/speed-settings">

                <table>
                  <tbody>
                    <tr>
                      <th>
                        <label for="disableGutenberg">Reativar editor clássico</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="disableGutenberg">
                          <input type="checkbox" name="disableGutenberg" value="1" class="toggle-switch-input" id="disableGutenberg" <?php checked($worker->get('disableGutenberg')) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                        </label>
                      </td>
                    </tr>
                    <tr>
                      <th>
                        <label for="disableBlockWidgets">Reativar widgets clássicos</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="disableBlockWidgets">
                          <input type="checkbox" name="disableBlockWidgets" value="1" class="toggle-switch-input" id="disableBlockWidgets" <?php checked($worker->get('disableBlockWidgets')) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                        </label>
                      </td>
                    </tr>
                    <tr>
                      <th>
                        <label for="disableDeprecatedComponents">Desativar componentes obsoletos</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="disableDeprecatedComponents">
                          <input type="checkbox" name="disableDeprecatedComponents" value="1" class="toggle-switch-input" id="disableDeprecatedComponents" <?php checked($worker->get('disableDeprecatedComponents')) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                        </label>
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="reduceHeartbeat">Controlar a API Heartbeat</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="reduceHeartbeat">
                          <input type="checkbox" name="reduceHeartbeat" value="1" class="toggle-switch-input" id="reduceHeartbeat" <?php checked($worker->get('reduceHeartbeat')) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                        </label>
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="disablePostRevisions">Desativar revisões</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="disablePostRevisions">
                          <input type="checkbox" name="disablePostRevisions" value="1" class="toggle-switch-input" id="disablePostRevisions" <?php checked($worker->get('disablePostRevisions')) ?>>
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
            </div>

            <div class="full-tab-panel analytics-view" id="custom-code">

              <?php $worker = new Full\Customer\Code\Settings(); ?>

              <style>
                .full-widget-logs {
                  background-color: black;
                  background-image: radial-gradient(rgba(0, 150, 0, 0.75), black 120%);
                  margin: 0;
                  overflow-x: hidden;
                  padding: 1em;
                  color: white;
                  font: 1rem Inconsolata, monospace;
                  text-shadow: 0 0 5px #C8C8C8;
                  border: 0;
                  position: relative;
                  height: 350px;
                  margin-bottom: 20px;
                }

                .full-widget-logs::after {
                  content: "";
                  position: absolute;
                  top: 0;
                  left: 0;
                  width: 100vw;
                  height: 100vh;
                  background: repeating-linear-gradient(0deg, rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0.15) 1px, transparent 1px, transparent 2px);
                  pointer-events: none;
                }

                .full-widget-logs ::selection {
                  background: #0080FF;
                  text-shadow: none;
                }

                .full-widget-logs pre {
                  margin: 0;
                }

                .full-widget-logs p {
                  line-height: 2;
                }
              </style>

              <?php if (filter_input(INPUT_GET, 'show-logs')) :
                $filename = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'debug.log';
                $clearFile = filter_input(INPUT_GET, 'empty-file');
                $valid     = file_exists($filename);

                if ($valid && $clearFile) :
                  fullFileSystem()->put_contents($filename, '');
                endif;

                $validSize  = $valid && filesize($filename) < 500000;
                $content    = $validSize ? fullFileSystem()->get_contents($filename) : 'Conteúdo indisponível ou muito grande para ser carregado no navegador.'
              ?>
                <h3>Log de erros do WordPress</h3>
                <p><a href="<?php echo esc_url(remove_query_arg(['show-logs', 'empty-file'])) ?>">Voltar para configurações</a></p>

                <div class="full-widget-form full-widget-logs">
                  <?php echo esc_html(wpautop(htmlspecialchars($content), true)) ?>
                </div>

                <a href="<?php echo esc_url(add_query_arg('empty-file', 1)) ?>" class="show-logs" style="color: red">Limpar arquivo</a>

                <br>
                <br>
                <br>
              <?php else : ?>

                <h3>Debug e testes</h3>
                <p>Configurações referentes ao modo de testes do <a href="https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/" target="_blank" rel="noopener noreferrer">WordPress</a></p>

                <form method="POST" id="full-debug" class="full-widget-form" style="margin-bottom: 60px">
                  <?php wp_nonce_field('full/widget/code/wp-config'); ?>
                  <input type="hidden" name="action" value="full/widget/code/wp-config">

                  <table>
                    <tbody>
                      <tr>
                        <th>
                          <label for="enableWpDebug">Ativar WP Debug</label>
                        </th>
                        <td>
                          <label class="toggle-switch toggle-switch-sm" for="enableWpDebug">
                            <input type="checkbox" name="enableWpDebug" value="1" class="toggle-switch-input" id="enableWpDebug" <?php checked($worker->getConst('WP_DEBUG')) ?>>
                            <span class="toggle-switch-label">
                              <span class="toggle-switch-indicator"></span>
                            </span>
                          </label>
                        </td>
                      </tr>
                      <tr>
                        <th>
                          <label for="enableWpDebugLog">Ativar Log</label>
                        </th>
                        <td>
                          <label class="toggle-switch toggle-switch-sm" for="enableWpDebugLog">
                            <input type="checkbox" name="enableWpDebugLog" value="1" class="toggle-switch-input requireWpDebug" id="enableWpDebugLog" <?php checked($worker->getConst('WP_DEBUG_LOG')) ?>>
                            <span class="toggle-switch-label">
                              <span class="toggle-switch-indicator"></span>
                            </span>
                          </label>
                        </td>
                      </tr>
                      <tr>
                        <th>
                          <label for="enableWpDebugDisplay">Exibir erros</label>
                        </th>
                        <td>
                          <label class="toggle-switch toggle-switch-sm" for="enableWpDebugDisplay">
                            <input type="checkbox" name="enableWpDebugDisplay" value="1" class="toggle-switch-input requireWpDebug" id="enableWpDebugDisplay" <?php checked($worker->getConst('WP_DEBUG_DISPLAY')) ?>>
                            <span class="toggle-switch-label">
                              <span class="toggle-switch-indicator"></span>
                            </span>
                          </label>
                        </td>
                      </tr>
                      <tr>
                        <th>
                          <button class="full-primary-button">Atualizar</button>
                          <a href="<?php echo esc_url(add_query_arg('show-logs', 1)) ?>" class="show-logs <?php echo $worker->getConst('WP_DEBUG_LOG') ? '' : 'hidden' ?>">Ver logs</a>
                        </th>
                        <td></td>
                      </tr>
                      </tr>
                    </tbody>
                  </table>
                </form>

                <?php foreach ($worker->getSections() as $section) : ?>

                  <h3><?php echo esc_html($section['name']) ?></h3>
                  <p><?php echo esc_html($section['instructions']) ?></p>

                  <form method="POST" id="full-<?php echo esc_html($section['key']) ?>" class="full-widget-form" style="margin-bottom: 60px; padding: 0; background-color: unset">
                    <?php wp_nonce_field('full/widget/code/' . $section['callback']); ?>
                    <input type="hidden" name="action" value="full/widget/code/<?php echo esc_html($section['callback']) ?>">
                    <input type="hidden" name="code" value="<?php echo esc_html($section['key']) ?>">

                    <textarea class="codemirror-code-value hidden" name="<?php echo esc_html($section['key']) ?>"><?php echo esc_html($worker->get($section['key'])) ?></textarea>
                    <textarea class="codemirror-code" data-mode="<?php echo esc_html($section['mode']) ?>"><?php echo esc_html($worker->get($section['key'])) ?></textarea>
                    <button class="full-primary-button" style="margin-top: 10px">Atualizar</button>
                  </form>

                <?php endforeach; ?>

              <?php endif; ?>
            </div>

            <div class="full-tab-panel analytics-view" id="wp-admin-custom">
              <?php $worker = new Full\Customer\Admin\Settings(); ?>

              <form method="POST" id="full-admin-settings" class="full-widget-form" style="margin-bottom: 30px">
                <?php wp_nonce_field('full/widget/admin-settings'); ?>
                <input type="hidden" name="action" value="full/widget/admin-settings">

                <table>
                  <tbody>
                    <tr>
                      <th>
                        <label for="clearTopBar">Limpar top bar</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="clearTopBar">
                          <input type="checkbox" name="clearTopBar" value="1" class="toggle-switch-input" id="clearTopBar" <?php checked($worker->get('clearTopBar')) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                        </label>
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="disableDashboardWidgets">Desativar widgets da tela inicial</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="disableDashboardWidgets">
                          <input type="checkbox" name="disableDashboardWidgets" value="1" class="toggle-switch-input" id="disableDashboardWidgets" <?php checked($worker->get('disableDashboardWidgets')) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                        </label>
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="hideAdminBarOnFrontend">Ocultar top bar no frontend do site</label>
                      </th>
                      <td>
                        <label class="toggle-switch toggle-switch-sm" for="hideAdminBarOnFrontend">
                          <input type="checkbox" name="hideAdminBarOnFrontend" value="1" class="toggle-switch-input" id="hideAdminBarOnFrontend" <?php checked($worker->get('hideAdminBarOnFrontend')) ?>>
                          <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                          </span>
                        </label>
                      </td>
                    </tr>

                    <tr>
                      <th>
                        <label for="sidebarWidth">Tamanho da barra lateral</label>
                      </th>
                      <td>
                        <select name="sidebarWidth" id="sidebarWidth" style="width: 100%">
                          <?php for ($i = 160; $i <= 300; $i += 20) : ?>
                            <option value="<?php echo esc_html($i) ?>" <?php selected($i, $worker->get('sidebarWidth')) ?>>
                              <?php echo esc_html($i) ?>px <?php echo esc_html(160 === $i ? '(tamanho padrão)' : '') ?>
                            </option>
                          <?php endfor; ?>
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

          </div>
        </div>
      </div>
    </div>
  </div>
</div>