<div class="templately-header">
  <div class="templately-logo">
    <img src="<?= fullGetImageUrl('logo-dark.png') ?>" alt="Logo FULL">
  </div>
  <div class="templately-nav-wrapper templately-menu">
    <ul class="templately-nav">
      <li class="tn-item templately-nav-item <?= 'pages' === $endpoint ? 'templately-nav-active' : '' ?>">
        <a href="<?= fullGetTemplatesUrl('pages') ?>">
          Páginas
        </a>
      </li>
      <li class="tn-item templately-nav-item <?= 'blocks' === $endpoint ? 'templately-nav-active' : '' ?>">
        <a href="<?= fullGetTemplatesUrl('blocks') ?>">
          Seções
        </a>
      </li>
      <li class="tn-item templately-nav-item <?= 'packs' === $endpoint ? 'templately-nav-active' : '' ?>">
        <a href="<?= fullGetTemplatesUrl('packs') ?>">
          Packs
        </a>
      </li>
      <li class="tn-item templately-nav-item <?= 'templates' === $endpoint ? 'templately-nav-active' : '' ?>">
        <a href="<?= fullGetTemplatesUrl('templates') ?>">
          Modelos
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