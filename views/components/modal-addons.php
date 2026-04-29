<?php

use FC\FileSystem;

$fs = FileSystem::instance();

?>

<div class="modal fade" id="modalAddons" tabindex="-1" aria-labelledby="modalAddonsTitulo" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable fs-modal-addons">
    <div class="modal-content fs-modal-addons__conteudo">
      <div class="modal-header fs-modal-addons__cabecalho">
        <div class="fs-modal-addons__titulo-grupo">
          <img src="<?= $fs->getUrl('assets/images/icons/dashboard-square-add.svg') ?>" alt="" width="24" height="24" />
          <h2 class="fs-modal-addons__titulo" id="modalAddonsTitulo">Addons</h2>
        </div>
        <button type="button" class="fs-sidebar__fechar" data-bs-dismiss="modal" aria-label="Fechar">
          <img src="<?= $fs->getUrl('assets/images/icons/multiplication-sign.svg') ?>" alt="" width="20" height="20" />
        </button>
      </div>
      <div class="modal-body fs-modal-addons__corpo">
        <!-- JetEngine -->
        <div class="fs-modal-addons__card">
          <div class="fs-modal-addons__card-topo">
            <img class="fs-modal-addons__card-logo" src="<?= $fs->getUrl('assets/images/addons/jet-engine.png'); ?>" alt="JetEngine" height="32" />
            <a href="#" class="fs-btn-ativar">
              <img src="<?= $fs->getUrl('assets/images/icons/energy-dark.svg') ?>" alt="" width="18" height="18" />
              Ativar
            </a>
          </div>
          <div class="fs-cartao-plugin__progresso">
            <div class="fs-cartao-plugin__barra-progresso">
              <div class="fs-cartao-plugin__barra-preenchimento" style="width: 0%"></div>
            </div>
            <span class="fs-cartao-plugin__texto-progresso">0/10 Sites</span>
          </div>
        </div>
        <!-- JetBlog -->
        <div class="fs-modal-addons__card">
          <div class="fs-modal-addons__card-topo">
            <img class="fs-modal-addons__card-logo" src="<?= $fs->getUrl('assets/images/addons/jet-blog.png'); ?>" alt="JetBlog" height="32" />
            <a href="#" class="fs-btn-ativar">
              <img src="<?= $fs->getUrl('assets/images/icons/energy-dark.svg') ?>" alt="" width="18" height="18" />
              Ativar
            </a>
          </div>
          <div class="fs-cartao-plugin__progresso">
            <div class="fs-cartao-plugin__barra-progresso">
              <div class="fs-cartao-plugin__barra-preenchimento" style="width: 0%"></div>
            </div>
            <span class="fs-cartao-plugin__texto-progresso">0/10 Sites</span>
          </div>
        </div>
        <!-- JetSmartFilters -->
        <div class="fs-modal-addons__card">
          <div class="fs-modal-addons__card-topo">
            <img class="fs-modal-addons__card-logo" src="<?= $fs->getUrl('assets/images/addons/jet-smartfilters.png'); ?>" alt="JetSmartFilters" height="32" />
            <a href="#" class="fs-btn-ativar">
              <img src="<?= $fs->getUrl('assets/images/icons/energy-dark.svg') ?>" alt="" width="18" height="18" />
              Ativar
            </a>
          </div>
          <div class="fs-cartao-plugin__progresso">
            <div class="fs-cartao-plugin__barra-progresso">
              <div class="fs-cartao-plugin__barra-preenchimento" style="width: 0%"></div>
            </div>
            <span class="fs-cartao-plugin__texto-progresso">0/10 Sites</span>
          </div>
        </div>
        <!-- JetTabs -->
        <div class="fs-modal-addons__card">
          <div class="fs-modal-addons__card-topo">
            <img class="fs-modal-addons__card-logo" src="<?= $fs->getUrl('assets/images/addons/jet-tabs.png'); ?>" alt="JetTabs" height="32" />
            <a href="#" class="fs-btn-ativar">
              <img src="<?= $fs->getUrl('assets/images/icons/energy-dark.svg') ?>" alt="" width="18" height="18" />
              Ativar
            </a>
          </div>
          <div class="fs-cartao-plugin__progresso">
            <div class="fs-cartao-plugin__barra-progresso">
              <div class="fs-cartao-plugin__barra-preenchimento" style="width: 0%"></div>
            </div>
            <span class="fs-cartao-plugin__texto-progresso">0/10 Sites</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>