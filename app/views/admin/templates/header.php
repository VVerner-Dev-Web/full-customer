<div class="templately-header">
  <div class="templately-logo">
    <img src="<?= fullGetImageUrl('logo-dark.png') ?>" alt="Logo FULL">
  </div>
  <div class="templately-nav-wrapper templately-menu">
    <ul class="templately-nav">
      <li class="tn-item templately-nav-item <?= 'templates' === $endpoint ? 'templately-nav-active' : '' ?>">
        <a href="<?= fullGetTemplatesUrl('templates') ?>">
          Templates
        </a>
      </li>
      <li class="tn-item templately-nav-item <?= 'cloud' === $endpoint ? 'templately-nav-active' : '' ?>">
        <a href="<?= fullGetTemplatesUrl('cloud') ?>">
          Cloud
        </a>
      </li>
    </ul>
  </div>
</div>