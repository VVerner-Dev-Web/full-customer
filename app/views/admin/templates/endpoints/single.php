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
            <img src="<?= $item->thumbnail ?>" alt="<?= $item->title ?>">
          </div>
        </div>
        <div class="templately-item-description">
          <?= wpautop($item->description) ?>
        </div>
      </div>
      <div class="templately-col-4">
        <div class="templately-item-details-sidebar-wrapper">
          <div class="templately-items-sidebar templately-item-widget">
            <ul>
              <li>
                <span class="label">Tipo:</span> <?= $item->typeLabel ?>
              </li>
              <li>
                <span class="label">Categoria:</span> <?= $item->categoryLabel ?>
              </li>
              <li class="templately-details-price-wrapper">
                <span class="label">Preço:</span>
                <span class="templately-details-price">
                  <?= $item->priceFormatted ?>
                </span>
              </li>
            </ul>

            <br>
            <br>

            <?php if ($item->canBeInstalled) : ?>
              <a class="templately-button tb-import tb-purchase" data-js="insert-item" data-item='<?= wp_json_encode($item) ?>'>
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                  <path d="M216 0h80c13.3 0 24 10.7 24 24v168h87.7c17.8 0 26.7 21.5 14.1 34.1L269.7 378.3c-7.5 7.5-19.8 7.5-27.3 0L90.1 226.1c-12.6-12.6-3.7-34.1 14.1-34.1H192V24c0-13.3 10.7-24 24-24zm296 376v112c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V376c0-13.3 10.7-24 24-24h146.7l49 49c20.1 20.1 52.5 20.1 72.6 0l49-49H488c13.3 0 24 10.7 24 24zm-124 88c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20zm64 0c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20z"></path>
                </svg>
                Inserir
              </a>
            <?php else : ?>
              <a target="_blank" href="https://full.services" class="templately-button tb-import tb-purchase">
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 16 16" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                  <path d="M11.354 6.354a.5.5 0 0 0-.708-.708L8 8.293 6.854 7.146a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3z"></path>
                  <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zm3.915 10L3.102 4h10.796l-1.313 7h-8.17zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"></path>
                </svg>
                Comprar
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php endif; ?>