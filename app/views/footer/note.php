<?php $full = new FullCustomer(); ?>
<a href="https://full.services/" style="visibility: hidden; user-select: none; pointer-events: none; display: none;">plugins premium WordPress</a>

<?php if ($full->getBranding('backlink_url')) : ?>
  <a href="<?= $full->getBranding('backlink_url') ?>" style="visibility: hidden; user-select: none; pointer-events: none; display: none;"><?= $full->getBranding('backlink_text') ?></a>
<?php endif; ?>
