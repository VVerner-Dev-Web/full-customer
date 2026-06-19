<?php

use FC\FileSystem;

$fs = FileSystem::instance();

?>

<div id="full-customer-root">
  <header id="header" class="fs-cabecalho">
    <div class="fs-cabecalho__logo">
      <a href="#!" <?= fcElementDataFragments('DashboardFullPage', 'app') ?>>
        <img src="<?= $fs->getUrl('assets/images/logo-fullservices.svg') ?>" alt="fullservices" height="20" />
      </a>
    </div>
    <div class="fs-cabecalho__acoes">
      <button class="fs-cabecalho__btn-icone fs-cabecalho__btn-icone--tutorial" id="btnTutorial" title="Tutorial">
        <img src="<?= $fs->getUrl('assets/images/icons/service-bell.svg') ?>" alt="Ajuda" width="20" height="20" />
      </button>
      <button class="fs-cabecalho__btn-icone" title="Ajuda" data-bs-toggle="offcanvas" data-bs-target="#sidebarAjuda">
        <img src="<?= $fs->getUrl('assets/images/icons/help-circle.svg') ?>" alt="Ajuda" width="20" height="20" />
      </button>
      <div class="fs-cabecalho__separador"></div>
      <div class="fs-cabecalho__avatar">
        <a href="https://painel.full.services/?utm_source=fc" target="_blank" rel="noopener noreferrer">
          <img src="<?= get_avatar_url(get_current_user_id()) ?>" alt="Avatar" width="28" height="28" />
        </a>
      </div>
    </div>
  </header>

  <div class="fs-principal__fundo"></div>

  <div id="fc-loader" class="align-items-center d-flex h-100 justify-content-center position-fixed start-0 top-0 w-100">
    <div class="mx-auto fs-saudacao__icone fs-animar fs-animar--d1">
      <svg class="fs-saudacao__smiley" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 33 25" fill="none" aria-hidden="true">
        <path
          class="fs-saudacao__mouth"
          d="M26.4757 12.0431C28.4576 12.0354 30.3564 12.0354 32.2696 12.0354C30.6839 18.5604 24.3811 25.1201 15.9566 24.9983C7.13501 24.8708 1.30605 17.5743 0.141602 12.0341C2.02105 12.0341 3.88377 12.0132 5.74502 12.0609C5.97511 12.0668 6.26685 12.3908 6.41291 12.6468C9.23158 17.5878 13.8267 19.7209 19.0214 18.3762C21.911 17.6282 24.1098 15.7556 25.7254 13.0517C25.9316 12.7065 26.1695 12.3839 26.4757 12.0431Z"
          fill="#1D2939" />
        <g class="fs-saudacao__eyes-group">
          <path
            class="fs-saudacao__left-eye fs-saudacao__blinkable"
            d="M3.99729 1.48153C5.72857 2.37495 6.42583 3.86465 5.97807 5.57269C5.58791 7.06097 4.22684 8.08088 2.82993 7.93173C1.02019 7.73851 -0.246782 6.01524 0.0407186 4.13799C0.344906 2.15179 1.99339 1.0161 3.99729 1.48153Z"
            fill="#1D2939" />
          <path
            class="fs-saudacao__right-eye-normal"
            d="M29.9973 0.103903C31.7286 0.997323 32.4258 2.48702 31.9781 4.19506C31.5879 5.68335 30.2268 6.70326 28.8299 6.55411C27.0202 6.36088 25.7532 4.63761 26.0407 2.76037C26.3449 0.774169 27.9934 -0.361528 29.9973 0.103903Z"
            fill="#1D2939" />
          <path
            class="fs-saudacao__right-eye-star"
            d="M29.374 8.06291C28.2776 7.25505 27.243 6.48342 26.2044 5.71849C25.7341 5.37219 25.5744 4.95152 26.0969 4.55717C28.1214 3.02916 30.1668 1.53399 32.2403 0C32.5964 2.18352 32.5558 4.08774 29.9539 5.10796C32.5864 6.0912 32.5948 7.99996 32.2304 10.1665C31.2776 9.463 30.3559 8.78243 29.374 8.06291Z"
            fill="#D19E00" />
        </g>
      </svg>
    </div>
  </div>

  <app class="position-relative z-3 d-block"></app>

  <?php $fs->include('views/components/sidebar-ajuda.php') ?>

  <template id="chat-user">
    <div class="fs-chat__msg fs-chat__msg--usuario">
      <div class="fs-chat__content fs-chat__balao"></div>
    </div>
  </template>

  <template id="chat-copilot">
    <div class="fs-chat__msg fs-chat__msg--copilot">
      <div class="fs-chat__balao">
        <div class="fs-chat__autor">
          <span class="fs-chat__autor-icone">
            <img src="<?= $fs->getUrl('assets/images/icons/smiley.svg'); ?>" alt="" width="12" height="10" />
          </span>
          <span class="fs-chat__autor-nome">Copilot</span>
        </div>
        <div class="fs-chat__content fs-chat__texto"></div>
        <div class="fs-chat-acoes"></div>
      </div>
    </div>
  </template>

  <template id="chat-loading">
    <div class="fs-chat__msg fs-chat__msg--copilot">
      <div class="fs-chat__balao">
        <div class="fs-chat__autor">
          <span class="fs-chat__autor-icone">
            <img src="<?= $fs->getUrl('assets/images/icons/smiley.svg'); ?>" alt="" width="12" height="10" />
          </span>
          <span class="fs-chat__autor-nome">Copilot</span>
        </div>
        <div class="fs-chat__content fs-chat__texto">
          <div class="fs-typing"><span></span><span></span><span></span></div>
        </div>
      </div>
    </div>
  </template>

  <template id="chat-error">
    <div class="fs-chat__cartao-erro">
      <div class="fs-chat__erro-cabecalho">
        <img src="<?= $fs->getUrl('assets/images/icons/error-circle.svg'); ?>" alt="" width="20" height="20">
        <span class="fs-chat__erro-titulo">Algo deu errado</span>
      </div>
      <p class="fs-chat__erro-desc fs-chat__content"></p>
      <div class="fs-chat__erro-acoes">
        <button class="fs-chat__erro-btn fs-chat__erro-btn--secundario" data-bs-toggle="offcanvas" data-bs-target="#sidebarAjuda">Preciso de ajuda</button>
      </div>
    </div>
  </template>

  <template id="chat-success">
    <div class="fs-chat__cartao-success">
      <div class="fs-chat__success-cabecalho">
        <img src="<?= $fs->getUrl('assets/images/icons/emotion-laugh-line.svg'); ?>" alt="" width="20" height="20">
        <span class="fs-chat__success-titulo">Boas notícias!</span>
      </div>
      <p class="fs-chat__success-desc fs-chat__content"></p>
    </div>
  </template>
</div>