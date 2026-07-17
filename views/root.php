<?php

use FC\FileSystem;

$fs = FileSystem::instance();

?>

<div id="full-customer-root">
  <div class="fs-principal__fundo"></div>

  <app class="position-relative z-3 d-block">
    <?php $fs->include('views/pages/dashboard.php'); ?>
  </app>

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
      <div class="fs-chat-acoes mt-3"></div>
    </div>
  </template>

  <template id="chat-success">
    <div class="fs-chat__cartao-success">
      <div class="fs-chat__success-cabecalho">
        <img src="<?= $fs->getUrl('assets/images/icons/emotion-laugh.svg'); ?>" alt="" width="20" height="20">
        <span class="fs-chat__success-titulo">Boas notícias!</span>
      </div>
      <p class="fs-chat__success-desc fs-chat__content"></p>
      <div class="fs-chat-acoes mt-3"></div>
    </div>
  </template>
</div>