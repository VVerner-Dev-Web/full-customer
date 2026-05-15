<?php

use FC\FileSystem;
?>
<div class="fs-admin-notice__banner">
  <div class="fs-admin-notice__banner-imagem">
    <img src="<?= FileSystem::instance()->getUrl('assets/images/plugue.png') ?>" alt="Conectar" />
  </div>
  <div class="fs-admin-notice__banner-barra"></div>
  <div class="fs-admin-notice__banner-conteudo">
    <div class="fs-admin-notice__banner-textos">
      <p class="fs-admin-notice__banner-nome">FULL Services</p>
      <p class="fs-admin-notice__banner-texto">Seu usuário está desconectado. Para aproveitar todos os benefícios da FULL, conecte seu site à sua conta FULL.</p>
    </div>
    <a href="<?= admin_url('admin.php?page=full') ?>" class="fs-admin-notice__banner-btn">Conectar site</a>
  </div>
</div>