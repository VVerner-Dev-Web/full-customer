<?php

use Full\Customer\Code\Settings;

$worker = new Settings();

?>

<div class="full-templates-admin-body">
  <div class="templately-wrapper">
    <div class="templately-header">
      <div class="templately-logo">
        <img src="<?= fullGetImageUrl('logo-dark-2.png') ?>" alt="Logo FULL">
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

          </div>
        </div>
      </div>
    </div>
  </div>
</div>