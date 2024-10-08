<?php if (isset($worker) && $worker->isButtonEnabledForSinglePost(get_the_ID())) : ?>

  <!-- Adicionado pela FULL. -->
  <div id="fc-whatsapp-container" class="<?= $worker->get('whatsappPosition') ?>">
    <a href="<?= is_singular() ? esc_url($worker->getSinglePostUrl(get_the_ID())) : esc_url($worker->getUrl()) ?>" target="_blank" rel="noopener noreferrer">
      <img src="<?= $worker->getLogoUrl() ?>" alt="Logo do WhatsApp" style="width: <?= $worker->get('whatsappLogoSize') ?>px">
    </a>
  </div>

<?php endif;
