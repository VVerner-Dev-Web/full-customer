<?php

use FC\FileSystem;

$fs = FileSystem::instance();

?>

<div class="offcanvas offcanvas-end fs-sidebar" tabindex="-1" id="sidebarAjuda" aria-labelledby="sidebarAjudaTitulo">
  <div class="offcanvas-header fs-sidebar__cabecalho">
    <div class="fs-sidebar__titulo-grupo">
      <img src="<?= $fs->getUrl('assets/images/icons/help-circle.svg') ?>" alt="" width="24" height="24" />
      <h2 class="fs-sidebar__titulo" id="sidebarAjudaTitulo">Ajuda</h2>
    </div>
    <button type="button" class="fs-sidebar__fechar" data-bs-dismiss="offcanvas" aria-label="Fechar">
      <img src="<?= $fs->getUrl('assets/images/icons/close.svg') ?>" alt="" width="20" height="20" />
    </button>
  </div>
  <div class="offcanvas-body fs-sidebar__corpo">
    <a href="https://help.full.services/?utm_source=fc" class="fs-sidebar__item" target="_blank"
      rel="noopener noreferrer">
      <div class="fs-sidebar__item-esquerda">
        <div class="fs-sidebar__item-icone">
          <img src="<?= $fs->getUrl('assets/images/icons/customer-service.svg') ?>" alt="" width="20" height="20" />
        </div>
        <div class="fs-sidebar__item-texto">
          <span class="fs-sidebar__item-nome">Suporte</span>
          <span class="fs-sidebar__item-desc">Fale com nosso time técnico e resolva qualquer questão diretamente pelo
            painel.</span>
        </div>
      </div>
      <img src="<?= $fs->getUrl('assets/images/icons/external-link.svg') ?>" alt="" width="20" height="20" />
    </a>
    <a href="https://help.full.services/?utm_source=fc" class="fs-sidebar__item" target="_blank"
      rel="noopener noreferrer">
      <div class="fs-sidebar__item-esquerda">
        <div class="fs-sidebar__item-icone">
          <img src="<?= $fs->getUrl('assets/images/icons/question-mark.svg') ?>" alt="" width="20" height="20" />
        </div>
        <div class="fs-sidebar__item-texto">
          <span class="fs-sidebar__item-nome">Perguntas Frequentes</span>
          <span class="fs-sidebar__item-desc">Encontre respostas para as dúvidas mais comuns sobre o FULL
            Copilot.</span>
        </div>
      </div>
      <img src="<?= $fs->getUrl('assets/images/icons/external-link.svg') ?>" alt="" width="20" height="20" />
    </a>
    <a href="https://help.full.services/?utm_source=fc" class="fs-sidebar__item" target="_blank"
      rel="noopener noreferrer">
      <div class="fs-sidebar__item-esquerda">
        <div class="fs-sidebar__item-icone">
          <img src="<?= $fs->getUrl('assets/images/icons/book-read.svg') ?>" alt="" width="20" height="20" />
        </div>
        <div class="fs-sidebar__item-texto">
          <span class="fs-sidebar__item-nome">Tutoriais</span>
          <span class="fs-sidebar__item-desc">Aprenda a usar todas as funcionalidades do FULL Copilot passo a
            passo.</span>
        </div>
      </div>
      <img src="<?= $fs->getUrl('assets/images/icons/external-link.svg') ?>" alt="" width="20" height="20" />
    </a>
    <a href="https://painel.full.services/?utm_source=fc" class="fs-sidebar__item" target="_blank"
      rel="noopener noreferrer">
      <div class="fs-sidebar__item-esquerda">
        <div class="fs-sidebar__item-icone">
          <img src="<?= $fs->getUrl('assets/images/icons/dashboard.svg') ?>" alt="" width="20" height="20" />
        </div>
        <div class="fs-sidebar__item-texto">
          <span class="fs-sidebar__item-nome">Planos e Créditos</span>
          <span class="fs-sidebar__item-desc">Gerencie seu plano, acompanhe limites e adicione créditos se
            precisar.</span>
        </div>
      </div>
      <img src="<?= $fs->getUrl('assets/images/icons/external-link.svg') ?>" alt="" width="20" height="20" />
    </a>
  </div>
</div>
