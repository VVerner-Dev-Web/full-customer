<?php

use FC\FileSystem;

$fs = FileSystem::instance();

?>

<div class="modal fade" id="modalUpgrade" tabindex="-1" aria-labelledby="modalUpgradeTitulo" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable fs-modal-upgrade">
    <div class="modal-content fs-modal-upgrade__conteudo">

      <!-- Header gradiente -->
      <div class="modal-header fs-modal-upgrade__cabecalho">
        <button type="button" class="fs-modal-upgrade__fechar" data-bs-dismiss="modal" aria-label="Fechar">
          <img src="<?= $fs->getUrl('assets/images/icons/multiplication-sign.svg') ?>" alt="" width="20" height="20">
        </button>
        <img class="fs-modal-upgrade__icone" src="<?= $fs->getUrl('assets/images/icons/crown.svg') ?>" alt="" width="42" height="42">
        <h2 class="fs-modal-upgrade__titulo" id="modalUpgradeTitulo">
          Desbloqueie o plano PRO e tenha o WordPress mais completo do Brasil
        </h2>
      </div>

      <!-- Corpo -->
      <div class="modal-body fs-modal-upgrade__corpo">

        <!-- Plano atual -->
        <div class="fs-modal-upgrade__plano-atual">
          <div class="fs-modal-upgrade__plano-atual-esquerda">
            <span class="fs-modal-upgrade__plano-atual-nome">FULL Basic</span>
            <span class="fs-modal-upgrade__plano-atual-sep"></span>
            <span class="fs-modal-upgrade__plano-atual-assentos">3 Assentos</span>
          </div>
          <span class="fs-modal-upgrade__plano-atual-emblema">✓ 10 ativações por plugin · distribua em qualquer site</span>
        </div>

        <!-- Divisor -->
        <div class="fs-modal-upgrade__divisor"></div>

        <!-- Seção de texto -->
        <div class="fs-modal-upgrade__secao-texto">
          <h3 class="fs-modal-upgrade__secao-titulo">Mais assentos, mesma stack completa</h3>
          <p class="fs-modal-upgrade__secao-desc">Todos os planos incluem os mesmos 14 plugins. O que muda é quantos sites você pode gerenciar.</p>
        </div>

        <!-- Card Advanced -->
        <div class="fs-modal-upgrade__card">
          <div class="fs-modal-upgrade__card-info">
            <h4 class="fs-modal-upgrade__card-nome">FULL Advanced</h4>
            <p class="fs-modal-upgrade__card-desc">5 ativ./plugin · distribua em até 70 sites</p>
          </div>
          <span class="fs-modal-upgrade__card-sep"></span>
          <div class="fs-modal-upgrade__card-preco">
            <span class="fs-modal-upgrade__card-preco-antigo">De: <s>R$ 699,90</s></span>
            <span class="fs-modal-upgrade__card-preco-atual">R$ <strong>549,90</strong> <small>/ano</small></span>
          </div>
          <a href="#" class="fs-modal-upgrade__card-btn">Upgrade</a>
        </div>

        <!-- Card PRO (destaque) -->
        <div class="fs-modal-upgrade__card-wrapper fs-modal-upgrade__card-wrapper--destaque">
          <div class="fs-modal-upgrade__card-banner">
            <img src="<?= $fs->getUrl('assets/images/icons/fire-minimalistic.svg') ?>" alt="" width="16" height="16">
            <span>Mais escolhido</span>
          </div>
          <div class="fs-modal-upgrade__card fs-modal-upgrade__card--destaque">
            <div class="fs-modal-upgrade__card-info">
              <h4 class="fs-modal-upgrade__card-nome">FULL PRO</h4>
              <p class="fs-modal-upgrade__card-desc">10 ativ./plugin · distribua em até 140 sites</p>
            </div>
            <span class="fs-modal-upgrade__card-sep"></span>
            <div class="fs-modal-upgrade__card-preco">
              <span class="fs-modal-upgrade__card-preco-antigo">De: <s>R$ 949,90</s></span>
              <span class="fs-modal-upgrade__card-preco-atual">R$ <strong>849,90</strong> <small>/ano</small></span>
            </div>
            <a href="#" class="fs-modal-upgrade__card-btn">Upgrade</a>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>