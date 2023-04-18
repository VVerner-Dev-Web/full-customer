<?php

use Full\Customer\Elementor\TemplateManager;

?>
<div class="templately-contents">

  <div class="templately-contents-header ">
    <div class="templately-contents-header-inner">
      <div class="templately-header-title">
        <h3>Todas as Páginas</h3>
      </div>
      <div class="templately-search">
        <input type="text" placeholder="Procurar" value="">
        <button class="templately-button templately-search-button">
          <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
            <path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path>
          </svg>
        </button>
      </div>
      <div class="templately-plan-switcher">
        <button data-plan="" class="components-button templately-plan-all templately-plan-active">Todos</button>
        <button data-plan="free" class="components-button templately-plan-starter">Grátis</button>
        <button data-plan="pro" class="components-button templately-plan-pro">Premium</button>
      </div>
    </div>
  </div>

  <div class="templately-items">

    <?php foreach (TemplateManager::instance()->listPages() as $item) : ?>
      <div class="templately-item templately-page-item" data-filter="<?= $item->priceTag ?>">
        <div class="templately-item-inner">
          <a class="templately-item-image-hover-wrapper " href="<?= $item->adminUrl ?>">
            <div class="templately-item-image-container ">
              <div class="templately-item-image-wrapper thumbnail-0">
                <div class="templately-badge templately-<?= $item->priceTag ?>">
                  <span><?= $item->price > 0 ? 'Premium' : 'Grátis' ?></span>
                </div>
                <img class="templately-item-image" width="100%" src="<?= $item->thumbnail ?>" alt="<?= $item->title ?>">
              </div>
            </div>
          </a>
          <div class="templately-item-details">
            <div class="templately-item-meta">
              <span class="templately-item-meta-single tb-item-price">
                <?= $item->priceFormatted ?>
              </span>

              <?php if ($item->canBeInstalled) : ?>
                <button class="templately-button templately-item-meta-single tt-top tb-item-insert" data-js="insert-item" data-item='<?= wp_json_encode($item) ?>'>
                  <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path d="M216 0h80c13.3 0 24 10.7 24 24v168h87.7c17.8 0 26.7 21.5 14.1 34.1L269.7 378.3c-7.5 7.5-19.8 7.5-27.3 0L90.1 226.1c-12.6-12.6-3.7-34.1 14.1-34.1H192V24c0-13.3 10.7-24 24-24zm296 376v112c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V376c0-13.3 10.7-24 24-24h146.7l49 49c20.1 20.1 52.5 20.1 72.6 0l49-49H488c13.3 0 24 10.7 24 24zm-124 88c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20zm64 0c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20z"></path>
                  </svg>
                  <span>Inserir</span>
                </button>
              <?php else : ?>
                <button onclick="window.location.href='https://full.services'" class="templately-button templately-item-meta-single tt-top tb-item-insert">
                  <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 16 16" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.354 6.354a.5.5 0 0 0-.708-.708L8 8.293 6.854 7.146a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3z"></path>
                    <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zm3.915 10L3.102 4h10.796l-1.313 7h-8.17zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"></path>
                  </svg>
                  Comprar
                </button>
              <?php endif; ?>
            </div>

            <a class="templately-title" href="<?= $item->adminUrl ?>">
              <h4>
                <?= $item->title ?>
              </h4>
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>