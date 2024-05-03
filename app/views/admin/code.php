<?php

use Full\Customer\Code\Settings;

$worker = new Settings();
?>

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
                <h3>FULL.code</h3>
              </div>
            </div>
          </div>

          <div class="full-page-content">

            <?php if (filter_input(INPUT_GET, 'show-logs')) :
              $filename = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'debug.log';
              $clearFile = filter_input(INPUT_GET, 'empty-file');
              $valid     = file_exists($filename);

              if ($valid && $clearFile) :
                file_put_contents($filename, '');
              endif;

              $validSize  = $valid && filesize($filename) < 500000;
              $content    = $validSize ? file_get_contents($filename) : 'Conteúdo indisponível ou muito grande para ser carregado no navegador.'
            ?>
              <h3>Log de erros do WordPress</h3>
              <p><a href="<?= remove_query_arg(['show-logs', 'empty-file']) ?>">Voltar para configurações</a></p>

              <div class="full-widget-form full-widget-logs">
                <?= wpautop(htmlspecialchars($content), true) ?>
              </div>

              <a href="<?= add_query_arg('empty-file', 1) ?>" class="show-logs" style="color: red">Limpar arquivo</a>

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
                        <a href="<?= add_query_arg('show-logs', 1) ?>" class="show-logs <?= $worker->getConst('WP_DEBUG_LOG') ? '' : 'hidden' ?>">Ver logs</a>
                      </th>
                      <td></td>
                    </tr>
                    </tr>
                  </tbody>
                </table>
              </form>

              <?php foreach ($worker->getSections() as $section) : ?>

                <h3><?= $section['name'] ?></h3>
                <p><?= $section['instructions'] ?></p>

                <form method="POST" id="full-<?= $section['key'] ?>" class="full-widget-form" style="margin-bottom: 60px; padding: 0; background-color: unset">
                  <?php wp_nonce_field('full/widget/code/' . $section['callback']); ?>
                  <input type="hidden" name="action" value="full/widget/code/<?= $section['callback'] ?>">
                  <input type="hidden" name="code" value="<?= $section['key'] ?>">

                  <textarea class="codemirror-code-value hidden" name="<?= $section['key'] ?>"><?= $worker->get($section['key']) ?></textarea>
                  <textarea class="codemirror-code" data-mode="<?= $section['mode'] ?>"><?= $worker->get($section['key']) ?></textarea>
                  <button class="full-primary-button" style="margin-top: 10px">Atualizar</button>
                </form>

              <?php endforeach; ?>

            <?php endif; ?>


          </div>
        </div>
      </div>
    </div>
  </div>
</div>