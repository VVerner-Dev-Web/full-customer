<?php

use Full\Customer\Elementor\TemplateManager; ?>
<div class="templately-sidebar templately-templates-sidebar">
  <div class="templately-collapse">
    <div class="tc-panel-item ts-single tc-panel-active">
      <div class="tc-panel-header tc-panel-header-active">
        <h4>Filtrar por categoria</h4>
      </div>
      <div class="tc-panel-body tc-content-active">
        <div class="templately-template-types">
          <ul id="full-template-filter">
            <?php foreach (TemplateManager::instance()->getCategories() as $category) : ?>
              <li>
                <label class="toggle-switch toggle-switch-sm" for="category-<?= $category->id ?>" style="margin-top: 1rem">
                  <input type="checkbox" value="<?= $category->id ?>" class="toggle-switch-input" id="category-<?= $category->id ?>">
                  <span class="toggle-switch-label">
                    <span class="toggle-switch-indicator"></span>
                  </span>
                  <span class="toggle-switch-content">
                    <span style="display: block;"><?= $category->name ?></span>
                  </span>
                </label>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="templately-contents">
  <div class="templately-contents-header ">
    <div class="templately-contents-header-inner">
      <div class="templately-header-title">
        <h3>Todas as Seções</h3>
      </div>

      <div class="templately-plan-switcher">
        <button data-plan="" class="components-button templately-plan-all templately-plan-active">Todos</button>
        <button data-plan="free" class="components-button templately-plan-starter">Grátis</button>
        <button data-plan="pro" class="components-button templately-plan-pro">Premium</button>
      </div>
    </div>
  </div>

  <div class="templately-items" id="response-container" data-page="1" data-type="block">
    <!-- JS -->
  </div>

  <div class="templately-my-clouds templately-has-no-items" id="no-items">
    <div class="templately-no-items">
      <div class="templately-no-items-inner">
        <img src="<?php echo esc_url(fullGetImageUrl('sorry.svg')) ?>" alt="" style="max-width: min(10rem, 80%);">
        <h3>Ops, nada encontrado</h3>
      </div>
    </div>
  </div>
</div>

<script type="text/template" id="tpl-templately-item">
  <div class="templately-item templately-page-item" data-filter="{priceTag}">
    <div class="templately-item-inner">
      <a class="templately-item-image-hover-wrapper " href="<?= fullGetTemplatesUrl('single') ?>&item={id}">
        <div class="templately-item-image-container ">
          <div class="templately-item-image-wrapper thumbnail-0">
            <div class="templately-badge templately-{priceTag}">
              <span>{priceTagLabel}</span>
            </div>
            <img class="templately-item-image" width="100%" src="{thumbnailUrl}" alt="{title}">
          </div>
        </div>
      </a>
      <div class="templately-item-details">
        <div class="templately-item-meta">
          <span class="templately-item-meta-single tb-item-price">
            {formattedPrice}
          </span>


          {button}
        </div>

        <a class="templately-title" href="<?= fullGetTemplatesUrl('single') ?>&item={id}">
          <h4>
            {title}
          </h4>
        </a>
      </div>
    </div>
  </div>
</script>

<script type="text/template" id="tpl-button-insert-item">
  <button class="templately-button templately-item-meta-single tt-top tb-item-insert" data-js="insert-item" data-item='{json}'>
    <i class="tio-download-from-cloud" style="margin-right: 5px;"></i>
    <span>Inserir</span>
  </button>
</script>

<script type="text/template" id="tpl-button-purchase-item">
  <button data-js="buy-item" class="templately-button templately-item-meta-single tt-top tb-item-insert" data-href='{purchaseUrl}'>
    <i class="tio-shopping-icon" style="margin-right: 5px;"></i>
    Comprar
  </button>
</script>