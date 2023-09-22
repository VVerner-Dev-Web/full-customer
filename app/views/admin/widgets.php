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
              <div class="templately-header-title">
                <h3>Controle de extensões</h3>
              </div>
            </div>
          </div>

          <div class="full-page-content">
            <div id="widgets-grid">


            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script id="widget-template" type="text-template">
  <div class="full-widget">
    <div class="icon">
      <img src="{icon}" alt="{name}">
    </div>
    <div class="description">
      <h4>
        {name}
        <span class="templately-badge templately-{tier}">
          <span>{tierLabel}</span>
        </span>
      </h4>
      <a href="{url}" target="_blank" rel="noopener noreferrer">Saiba mais</a>
    </div>
    <div class="status">
      <label class="toggle-switch-sm" for="full-widget-{key}">
        <input type="checkbox" value="{key}" class="toggle-switch-input" id="full-widget-{key}" {checked}>
        <span class="toggle-switch-label">
          <span class="toggle-switch-indicator"></span>
        </span>
      </label>
    </div>
  </div>
</script>