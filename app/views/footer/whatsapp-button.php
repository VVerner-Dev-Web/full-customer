<?php if (isset($worker)) : ?>

  <!-- Adicionado pela FULL. -->
  <div id="fc-whatsapp-container" class="<?= $worker->get('whatsappPosition') ?>">
    <a href="<?= $worker->getUrl() ?>" target="_blank" rel="noopener noreferrer">
      <img src="<?= $worker->getLogoUrl() ?>" alt="Logo do WhatsApp" style="width: <?= $worker->get('whatsappLogoSize') ?>px">
    </a>
  </div>

<?php endif;
