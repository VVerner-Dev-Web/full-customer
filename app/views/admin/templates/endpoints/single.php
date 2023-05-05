<?php

use Full\Customer\Elementor\TemplateManager;

$itemId = filter_input(INPUT_GET, 'item', FILTER_VALIDATE_INT);
$item   = TemplateManager::instance()->getItem($itemId);
?>

<?php if (!$item) : ?>

  <div class="templately-contents templately-item-details">
    <div class="templately-item-details-header">
      <div class="templately-row align-center justify-between">
        <div class="templately-col">
          <div class="templately-items-header">
            <h3>Item não localizado</h3>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php else : ?>

  <div class="templately-contents templately-item-details">
    <div class="templately-item-details-header">
      <div class="templately-row align-center justify-between">
        <div class="templately-col">
          <div class="templately-items-header">
            <h1><?= $item->title ?></h1>
          </div>
        </div>
      </div>
    </div>
    <div class="templately-row">
      <div class="templately-col-8">
        <div class="templately-items-banner-wrapper">
          <div class="templately-items-banner">
            <div class="templately-badge templately-<?= $item->priceTag ?> templately-details-banner-badge">
              <span>
                <?= $item->price > 0 ? 'Premium' : 'Grátis' ?>
              </span>
            </div>
            <img src="<?= $item->thumbnailUrl ?>" alt="<?= $item->title ?>">
          </div>
        </div>
        <div class="templately-item-description">
          <?= $item->description ?>
        </div>
      </div>
      <div class="templately-col-4">
        <div class="templately-item-details-sidebar-wrapper">
          <div class="templately-items-sidebar templately-item-widget">
            <ul>
              <li>
                <span class="label">Categorias:</span> <?= implode(', ', wp_list_pluck($item->categories, 'name')) ?>
              </li>
              <li class="templately-details-price-wrapper">
                <span class="label">Preço:</span>
                <span class="templately-details-price">
                  <?= $item->formattedPrice ?>
                </span>
              </li>
            </ul>

            <br>
            <br>

            <?php if ($item->canBeInstalled) : ?>
              <a class="templately-button tb-import tb-purchase" data-js="insert-item" data-item='<?= wp_json_encode($item) ?>' style="background-color: #eabc32; margin-right: 1em;">
                <i class="tio-download-from-cloud" style="margin-right: 5px;"></i>
                Inserir
              </a>
            <?php else : ?>
              <a target="_blank" href="<?= $item->purchaseUrl ?>" class="templately-button tb-import tb-purchase" style="background-color: #eabc32">
                <i class="tio-shopping-icon" style="margin-right: 5px;"></i>
                Comprar
              </a>
            <?php endif; ?>

            <a target="_blank" href="<?= $item->purchaseUrl ?>" class="templately-button tb-import tb-purchase">
              <i class="tio-visible-outlined" style="margin-right: 5px;"></i>
              Mais detalhes
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php endif; ?>