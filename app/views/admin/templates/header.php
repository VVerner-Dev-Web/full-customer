<div class="templately-header">
  <div class="templately-logo">
    <img src="<?= fullGetImageUrl('logo-dark-2.png') ?>" alt="Logo FULL">
  </div>
  <div class="templately-nav-wrapper templately-menu">
    <ul class="templately-nav">
      <li class="tn-item templately-nav-item <?= 'templates' === $endpointView ? 'templately-nav-active' : '' ?>">
        <a href="<?= fullGetTemplatesUrl('templates') ?>" data-endpoint="templates">
          Templates
        </a>
      </li>
      <li class="tn-item templately-nav-item <?= 'cloud' === $endpointView ? 'templately-nav-active' : '' ?>">
        <a href="<?= fullGetTemplatesUrl('cloud') ?>" data-endpoint="cloud">
          Cloud
        </a>
      </li>
    </ul>
  </div>
</div>