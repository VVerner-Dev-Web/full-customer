<?php

use FULL\Customer\Shortcodes\Collection;
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
                <h3>FULL.shortcodes</h3>
              </div>
            </div>
          </div>

          <div class="full-page-content">

            <form method="POST" id="full-email-settings" class="full-widget-form" style="margin-bottom: 30px">
              <table>
                <thead>
                  <tr>
                    <th>Shortcode</th>
                    <th>Exemplo de retorno</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach (Collection::list() as $shortcode) : ?>
                    <tr>
                      <th>
                        <span class="copy-on-click">[<?= $shortcode ?>]</span>
                      </th>
                      <td>
                        <?= apply_shortcodes("[$shortcode]") ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>