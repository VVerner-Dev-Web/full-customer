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
      <button class="fs-cabecalho__selo-pro" data-bs-toggle="modal" data-bs-target="#modalUpgrade">
        <img src="<?= $fs->getUrl('assets/images/icons/crown.svg') ?>" alt="" width="16" height="16" />
        <span>PRO <span class="fs-cabecalho__pro-sep">&middot;</span> <span class="fs-cabecalho__pro-leve">10 ativ./plugin</span></span>
      </button>
      <button class="fs-cabecalho__btn-icone" title="Ajuda" data-bs-toggle="offcanvas" data-bs-target="#sidebarAjuda">
        <img src="<?= $fs->getUrl('assets/images/icons/help-circle.svg') ?>" alt="Ajuda" width="20" height="20" />
      </button>
      <button class="fs-cabecalho__btn-icone" title="Sidebar" data-bs-toggle="offcanvas" data-bs-target="#sidebarCentral">
        <img src="<?= $fs->getUrl('assets/images/icons/view-sidebar-right.svg') ?>" alt="Sidebar" width="20" height="20" />
      </button>
      <div class="fs-cabecalho__separador"></div>
      <div class="fs-cabecalho__avatar">
        <img src="<?= $fs->getUrl('assets/images/avatar.png') ?>" alt="Avatar" width="28" height="28" />
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

  <div class="offcanvas offcanvas-end fs-sidebar" tabindex="-1" id="sidebarAjuda" aria-labelledby="sidebarAjudaTitulo">
    <div class="offcanvas-header fs-sidebar__cabecalho">
      <div class="fs-sidebar__titulo-grupo">
        <img src="<?= $fs->getUrl('assets/images/icons/help-circle.svg') ?>" alt="" width="24" height="24" />
        <h2 class="fs-sidebar__titulo" id="sidebarAjudaTitulo">Ajuda</h2>
      </div>
      <button type="button" class="fs-sidebar__fechar" data-bs-dismiss="offcanvas" aria-label="Fechar">
        <img src="<?= $fs->getUrl('assets/images/icons/multiplication-sign.svg') ?>" alt="" width="20" height="20" />
      </button>
    </div>
    <div class="offcanvas-body fs-sidebar__corpo">
      <a href="#" class="fs-sidebar__item">
        <div class="fs-sidebar__item-esquerda">
          <div class="fs-sidebar__item-icone">
            <img src="<?= $fs->getUrl('assets/images/icons/customer-service.svg') ?>" alt="" width="20" height="20" />
          </div>
          <div class="fs-sidebar__item-texto">
            <span class="fs-sidebar__item-nome">Suporte</span>
            <span class="fs-sidebar__item-desc">Fale com nosso time técnico e resolva qualquer questão diretamente pelo painel.</span>
          </div>
        </div>
        <img class="fs-sidebar__item-seta" src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg') ?>" alt="" width="20" height="20" />
      </a>
      <a href="#" class="fs-sidebar__item">
        <div class="fs-sidebar__item-esquerda">
          <div class="fs-sidebar__item-icone">
            <img src="<?= $fs->getUrl('assets/images/icons/help-circle-yellow.svg') ?>" alt="" width="20" height="20" />
          </div>
          <div class="fs-sidebar__item-texto">
            <span class="fs-sidebar__item-nome">Perguntas Frequentes</span>
            <span class="fs-sidebar__item-desc">Encontre respostas para as dúvidas mais comuns sobre o FULL Copilot.</span>
          </div>
        </div>
        <img class="fs-sidebar__item-seta" src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg') ?>" alt="" width="20" height="20" />
      </a>
      <a href="#" class="fs-sidebar__item">
        <div class="fs-sidebar__item-esquerda">
          <div class="fs-sidebar__item-icone">
            <img src="<?= $fs->getUrl('assets/images/icons/book-open.svg') ?>" alt="" width="20" height="20" />
          </div>
          <div class="fs-sidebar__item-texto">
            <span class="fs-sidebar__item-nome">Tutoriais</span>
            <span class="fs-sidebar__item-desc">Aprenda a usar todas as funcionalidades do FULL Copilot passo a passo.</span>
          </div>
        </div>
        <img class="fs-sidebar__item-seta" src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg') ?>" alt="" width="20" height="20" />
      </a>
      <a href="#" class="fs-sidebar__item">
        <div class="fs-sidebar__item-esquerda">
          <div class="fs-sidebar__item-icone">
            <img src="<?= $fs->getUrl('assets/images/icons/money.svg') ?>" alt="" width="20" height="20" />
          </div>
          <div class="fs-sidebar__item-texto">
            <span class="fs-sidebar__item-nome">Planos e Créditos</span>
            <span class="fs-sidebar__item-desc">Gerencie seu plano, acompanhe limites e adicione créditos se precisar.</span>
          </div>
        </div>
        <img class="fs-sidebar__item-seta" src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg') ?>" alt="" width="20" height="20" />
      </a>
    </div>
  </div>

  <div class="offcanvas offcanvas-end fs-sidebar" tabindex="-1" id="sidebarCentral" aria-labelledby="sidebarCentralTitulo">
    <!-- Vista: Lista -->
    <div id="centralLista">
      <div class="offcanvas-header fs-sidebar__cabecalho">
        <div class="fs-sidebar__titulo-grupo">
          <img src="<?= $fs->getUrl('assets/images/icons/plugin-fullai.svg') ?>" alt="" width="26" height="20" />
          <h2 class="fs-sidebar__titulo" id="sidebarCentralTitulo">Central FULL</h2>
        </div>
        <button type="button" class="fs-sidebar__fechar" data-bs-dismiss="offcanvas" aria-label="Fechar">
          <img src="<?= $fs->getUrl('assets/images/icons/multiplication-sign.svg') ?>" alt="" width="20" height="20" />
        </button>
      </div>
      <div class="fs-sidebar__cabecalho-extra">
        <div class="fs-sidebar__divisor"></div>
        <div class="fs-sidebar__tabs" id="centralTabs">
          <button class="fs-sidebar__tab fs-sidebar__tab--ativo" data-filtro="todos">
            <img src="<?= $fs->getUrl('assets/images/icons/dashboard-square.svg') ?>" alt="" width="18" height="18" />
            Todos
          </button>
          <button class="fs-sidebar__tab" data-filtro="ativacao">
            <img src="<?= $fs->getUrl('assets/images/icons/energy.svg') ?>" alt="" width="18" height="18" />
            Ativações
          </button>
          <button class="fs-sidebar__tab" data-filtro="ticket">
            <img src="<?= $fs->getUrl('assets/images/icons/ticket.svg') ?>" alt="" width="18" height="18" />
            Tickets
          </button>
          <button class="fs-sidebar__tab" data-filtro="sistema">
            <img src="<?= $fs->getUrl('assets/images/icons/settings.svg') ?>" alt="" width="18" height="18" />
            Sistema
          </button>
        </div>
      </div>
      <div class="offcanvas-body fs-sidebar__corpo fs-sidebar__corpo--lista" id="centralCorpo">
        <!-- Hoje -->
        <div class="fs-sidebar__grupo" data-grupo>
          <span class="fs-sidebar__grupo-data">Hoje</span>

          <div class="fs-sidebar__item fs-sidebar__item--log" data-tipo="ativacao" data-ver-log>
            <div class="fs-sidebar__item-esquerda">
              <div class="fs-sidebar__item-icone fs-sidebar__item-icone--amarelo">
                <img src="<?= $fs->getUrl('assets/images/icons/energy.svg') ?>" alt="" width="20" height="20" />
              </div>
              <div class="fs-sidebar__item-texto">
                <span class="fs-sidebar__item-nome">Elementor PRO ativado</span>
                <span class="fs-sidebar__item-desc">Agora · Via Copilot</span>
              </div>
            </div>
            <img class="fs-sidebar__item-seta" src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg') ?>" alt="" width="20" height="20" />
          </div>

          <div class="fs-sidebar__item fs-sidebar__item--log" data-tipo="ativacao" data-ver-log>
            <div class="fs-sidebar__item-esquerda">
              <div class="fs-sidebar__item-icone fs-sidebar__item-icone--amarelo">
                <img src="<?= $fs->getUrl('assets/images/icons/energy.svg') ?>" alt="" width="20" height="20" />
              </div>
              <div class="fs-sidebar__item-texto">
                <span class="fs-sidebar__item-nome">Elementor PRO ativado</span>
                <span class="fs-sidebar__item-desc">14:32 · Via Copilot</span>
              </div>
            </div>
            <img class="fs-sidebar__item-seta" src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg') ?>" alt="" width="20" height="20" />
          </div>

          <div class="fs-sidebar__item fs-sidebar__item--log" data-tipo="ativacao" data-ver-log>
            <div class="fs-sidebar__item-esquerda">
              <div class="fs-sidebar__item-icone fs-sidebar__item-icone--amarelo">
                <img src="<?= $fs->getUrl('assets/images/icons/energy.svg') ?>" alt="" width="20" height="20" />
              </div>
              <div class="fs-sidebar__item-texto">
                <span class="fs-sidebar__item-nome">Crocoblock PRO ativado</span>
                <span class="fs-sidebar__item-desc">14:30 · Via Copilot</span>
              </div>
            </div>
            <img class="fs-sidebar__item-seta" src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg') ?>" alt="" width="20" height="20" />
          </div>

          <div class="fs-sidebar__item fs-sidebar__item--log" data-tipo="ticket" data-ver-ticket="1042">
            <div class="fs-sidebar__item-esquerda">
              <div class="fs-sidebar__item-icone">
                <img src="<?= $fs->getUrl('assets/images/icons/ticket.svg') ?>" alt="" width="20" height="20" />
              </div>
              <div class="fs-sidebar__item-texto">
                <span class="fs-sidebar__item-nome">Ticket #1042 · <span class="fs-status--aberto">Aberto</span></span>
                <span class="fs-sidebar__item-desc">Técnico FULL respondeu · 11:20</span>
              </div>
            </div>
            <img class="fs-sidebar__item-seta" src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg') ?>" alt="" width="20" height="20" />
          </div>

          <div class="fs-sidebar__item fs-sidebar__item--log" data-tipo="sistema">
            <div class="fs-sidebar__item-esquerda">
              <div class="fs-sidebar__item-icone">
                <img src="<?= $fs->getUrl('assets/images/icons/settings.svg') ?>" alt="" width="20" height="20" />
              </div>
              <div class="fs-sidebar__item-texto">
                <span class="fs-sidebar__item-nome">Ativações FULL habilitado</span>
                <span class="fs-sidebar__item-desc">09:00 · Sistema</span>
              </div>
            </div>
            <img class="fs-sidebar__item-seta" src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg') ?>" alt="" width="20" height="20" />
          </div>
        </div>

        <!-- Ontem -->
        <div class="fs-sidebar__grupo" data-grupo>
          <span class="fs-sidebar__grupo-data">Ontem</span>

          <div class="fs-sidebar__item fs-sidebar__item--log" data-tipo="ativacao" data-ver-log>
            <div class="fs-sidebar__item-esquerda">
              <div class="fs-sidebar__item-icone fs-sidebar__item-icone--amarelo">
                <img src="<?= $fs->getUrl('assets/images/icons/energy.svg') ?>" alt="" width="20" height="20" />
              </div>
              <div class="fs-sidebar__item-texto">
                <span class="fs-sidebar__item-nome">WP Rocket ativado</span>
                <span class="fs-sidebar__item-desc">16:44 · Via Copilot</span>
              </div>
            </div>
            <img class="fs-sidebar__item-seta" src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg') ?>" alt="" width="20" height="20" />
          </div>

          <div class="fs-sidebar__item fs-sidebar__item--log" data-tipo="ativacao" data-ver-log>
            <div class="fs-sidebar__item-esquerda">
              <div class="fs-sidebar__item-icone fs-sidebar__item-icone--erro">
                <img src="<?= $fs->getUrl('assets/images/icons/energy-amber-small.svg') ?>" alt="" width="20" height="20" />
              </div>
              <div class="fs-sidebar__item-texto">
                <span class="fs-sidebar__item-nome">Erro ao ativar ACF PRO</span>
                <span class="fs-sidebar__item-desc">15:20 · Timeout no servidor</span>
              </div>
            </div>
            <img class="fs-sidebar__item-seta" src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg') ?>" alt="" width="20" height="20" />
          </div>

          <div class="fs-sidebar__item fs-sidebar__item--log" data-tipo="ticket" data-ver-ticket="1040">
            <div class="fs-sidebar__item-esquerda">
              <div class="fs-sidebar__item-icone">
                <img src="<?= $fs->getUrl('assets/images/icons/ticket.svg') ?>" alt="" width="20" height="20" />
              </div>
              <div class="fs-sidebar__item-texto">
                <span class="fs-sidebar__item-nome">Ticket #1040 · <span class="fs-status--fechado">Fechado</span></span>
                <span class="fs-sidebar__item-desc">Suporte técnico · 10:30</span>
              </div>
            </div>
            <img class="fs-sidebar__item-seta" src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg') ?>" alt="" width="20" height="20" />
          </div>
        </div>

        <!-- 15 mar 2026 -->
        <div class="fs-sidebar__grupo" data-grupo>
          <span class="fs-sidebar__grupo-data">15 mar 2026</span>

          <div class="fs-sidebar__item fs-sidebar__item--log" data-tipo="ativacao" data-ver-log>
            <div class="fs-sidebar__item-esquerda">
              <div class="fs-sidebar__item-icone fs-sidebar__item-icone--amarelo">
                <img src="<?= $fs->getUrl('assets/images/icons/energy.svg') ?>" alt="" width="20" height="20" />
              </div>
              <div class="fs-sidebar__item-texto">
                <span class="fs-sidebar__item-nome">Rank Math PRO ativado</span>
                <span class="fs-sidebar__item-desc">11:30 · Via Copilot</span>
              </div>
            </div>
            <img class="fs-sidebar__item-seta" src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg') ?>" alt="" width="20" height="20" />
          </div>

          <div class="fs-sidebar__item fs-sidebar__item--log" data-tipo="ativacao" data-ver-log>
            <div class="fs-sidebar__item-esquerda">
              <div class="fs-sidebar__item-icone fs-sidebar__item-icone--amarelo">
                <img src="<?= $fs->getUrl('assets/images/icons/energy.svg') ?>" alt="" width="20" height="20" />
              </div>
              <div class="fs-sidebar__item-texto">
                <span class="fs-sidebar__item-nome">Astra PRO ativado</span>
                <span class="fs-sidebar__item-desc">11:28 · Via Copilot</span>
              </div>
            </div>
            <img class="fs-sidebar__item-seta" src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg') ?>" alt="" width="20" height="20" />
          </div>

          <div class="fs-sidebar__item fs-sidebar__item--log" data-tipo="sistema">
            <div class="fs-sidebar__item-esquerda">
              <div class="fs-sidebar__item-icone">
                <img src="<?= $fs->getUrl('assets/images/icons/settings.svg') ?>" alt="" width="20" height="20" />
              </div>
              <div class="fs-sidebar__item-texto">
                <span class="fs-sidebar__item-nome">Plugin FULL v2.4.1 instalado</span>
                <span class="fs-sidebar__item-desc">08:00 · Sistema</span>
              </div>
            </div>
            <img class="fs-sidebar__item-seta" src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg') ?>" alt="" width="20" height="20" />
          </div>
        </div>

        <!-- Estado vazio por filtro -->
        <div class="fs-central__vazio fs-oculto" id="centralVazio">
          <div class="fs-central__vazio-icone" id="centralVazioIcone"></div>
          <p class="fs-central__vazio-titulo" id="centralVazioTitulo"></p>
          <p class="fs-central__vazio-desc" id="centralVazioDesc"></p>
        </div>
      </div>
    </div>

    <!-- Vista: Detalhe de Log -->
    <div id="centralDetalhe" class="fs-sidebar__detalhe-wrapper fs-oculto">
      <div class="offcanvas-header fs-sidebar__cabecalho">
        <button class="fs-sidebar__voltar" data-voltar-central>
          <img src="<?= $fs->getUrl('assets/images/icons/arrow-left.svg') ?>" alt="" width="16" height="16" />
          Voltar
        </button>
        <button type="button" class="fs-sidebar__fechar" data-bs-dismiss="offcanvas" aria-label="Fechar">
          <img src="<?= $fs->getUrl('assets/images/icons/multiplication-sign.svg') ?>" alt="" width="20" height="20" />
        </button>
      </div>
      <div class="offcanvas-body fs-sidebar__corpo fs-sidebar__corpo--detalhe">
        <div class="fs-sidebar__detalhe-topo">
          <div class="fs-sidebar__detalhe-plugin">
            <img src="<?= $fs->getUrl('assets/images/icons/plugin-elementor-circle.svg') ?>" alt="" width="42" height="42" />
            <div class="fs-sidebar__detalhe-info">
              <span class="fs-sidebar__detalhe-nome">Elementor PRO</span>
              <div class="fs-sidebar__detalhe-meta">
                <span class="fs-sidebar__log-desc">18 mar 2026 · 14:32</span>
                <span class="fs-emblema fs-emblema--sucesso"><span class="fs-emblema__ponto"></span>Sucesso</span>
              </div>
            </div>
          </div>
          <div class="fs-sidebar__divisor"></div>
          <div class="fs-sidebar__timeline-section">
            <span class="fs-sidebar__log-desc">Timeline da ativação</span>
            <div class="fs-sidebar__timeline">
              <div class="fs-sidebar__timeline-step">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="fs-sidebar__timeline-icone">
                  <circle cx="9" cy="9" r="9" fill="#FFD700" />
                  <circle cx="9" cy="9" r="4" fill="#182230" />
                </svg>
                <div class="fs-sidebar__log-texto"><span class="fs-sidebar__log-titulo">Solicitação recebida via Copilot</span><span class="fs-sidebar__log-desc">14:32:00</span></div>
              </div>
              <div class="fs-sidebar__timeline-step">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="fs-sidebar__timeline-icone">
                  <circle cx="9" cy="9" r="9" fill="#DCFAE6" />
                  <path d="M5.5 9L7.8 11.5L12.5 6.5" stroke="#12B76A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="fs-sidebar__log-texto"><span class="fs-sidebar__log-titulo">Plugin localizado no repositório FULL</span><span class="fs-sidebar__log-desc">14:32:01</span></div>
              </div>
              <div class="fs-sidebar__timeline-step">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="fs-sidebar__timeline-icone">
                  <circle cx="9" cy="9" r="9" fill="#DCFAE6" />
                  <path d="M5.5 9L7.8 11.5L12.5 6.5" stroke="#12B76A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="fs-sidebar__log-texto"><span class="fs-sidebar__log-titulo">Download concluído</span><span class="fs-sidebar__log-desc">14:32:06</span></div>
              </div>
              <div class="fs-sidebar__timeline-step">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="fs-sidebar__timeline-icone">
                  <circle cx="9" cy="9" r="9" fill="#DCFAE6" />
                  <path d="M5.5 9L7.8 11.5L12.5 6.5" stroke="#12B76A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="fs-sidebar__log-texto"><span class="fs-sidebar__log-titulo">Plugin instalado com sucesso</span><span class="fs-sidebar__log-desc">14:32:09</span></div>
              </div>
              <div class="fs-sidebar__timeline-step">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="fs-sidebar__timeline-icone">
                  <circle cx="9" cy="9" r="9" fill="#DCFAE6" />
                  <path d="M5.5 9L7.8 11.5L12.5 6.5" stroke="#12B76A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="fs-sidebar__log-texto"><span class="fs-sidebar__log-titulo">Licença ativada</span><span class="fs-sidebar__log-desc">14:32:10</span></div>
              </div>
            </div>
          </div>
        </div>
        <div class="fs-sidebar__detalhe-rodape">
          <span class="fs-sidebar__log-desc">Duração: <strong>11s</strong> · Por: <strong>admin</strong></span>
          <button class="fs-sidebar__detalhe-desfazer">
            <img src="<?= $fs->getUrl('assets/images/icons/arrow-turn-backward.svg') ?>" alt="" width="16" height="16" />
            Desfazer
          </button>
        </div>
      </div>
    </div>

    <!-- Vista: Ticket -->
    <div id="centralTicket" class="fs-sidebar__detalhe-wrapper fs-oculto">
      <div class="offcanvas-header fs-sidebar__cabecalho">
        <button class="fs-sidebar__voltar" data-voltar-central>
          <img src="<?= $fs->getUrl('assets/images/icons/arrow-left.svg') ?>" alt="" width="16" height="16" />
          Voltar
        </button>
        <button type="button" class="fs-sidebar__fechar" data-bs-dismiss="offcanvas" aria-label="Fechar">
          <img src="<?= $fs->getUrl('assets/images/icons/multiplication-sign.svg') ?>" alt="" width="20" height="20" />
        </button>
      </div>
      <div class="fs-ticket__cabecalho">
        <div class="fs-ticket__info">
          <span class="fs-ticket__numero" id="ticketNumero">Ticket #1042</span>
          <span class="fs-emblema fs-emblema--sucesso" id="ticketStatus"><span class="fs-emblema__ponto"></span>Aberto</span>
        </div>
        <p class="fs-ticket__assunto" id="ticketAssunto">Plugin Elementor PRO não ativa após instalação</p>
      </div>
      <div class="fs-sidebar__divisor fs-sidebar__divisor--ticket"></div>
      <div class="fs-ticket__conversa" id="ticketConversa">
        <div class="fs-ticket__msg fs-ticket__msg--usuario">
          <div class="fs-ticket__balao">
            <p>Olá, instalei o Elementor PRO mas ele não está ativando. Aparece um erro de licença inválida.</p>
          </div>
          <span class="fs-ticket__msg-meta">Você · 27 mar 2026, 10:15</span>
        </div>
        <div class="fs-ticket__msg fs-ticket__msg--suporte">
          <div class="fs-ticket__avatar">FS</div>
          <div class="fs-ticket__msg-corpo">
            <div class="fs-ticket__balao">
              <p>Olá! Identificamos o problema. O Elementor PRO precisa ser ativado via FULL Copilot, não diretamente pelo wp-admin.</p>
            </div>
            <span class="fs-ticket__msg-meta">Suporte FULL · 27 mar 2026, 11:32</span>
          </div>
        </div>
        <div class="fs-ticket__msg fs-ticket__msg--usuario">
          <div class="fs-ticket__balao">
            <p>Tentei pelo Copilot mas agora diz "site inacessível". O que faço?</p>
          </div>
          <span class="fs-ticket__msg-meta">Você · 27 mar 2026, 11:45</span>
        </div>
        <div class="fs-ticket__msg fs-ticket__msg--suporte fs-ticket__msg--nova">
          <div class="fs-ticket__avatar">FS</div>
          <div class="fs-ticket__msg-corpo">
            <div class="fs-ticket__balao">
              <p>Enviamos uma correção automática — tente ativar novamente agora pelo Copilot e me confirma se funcionou.</p>
            </div>
            <span class="fs-ticket__msg-meta">Técnico FULL · Hoje, 11:20 · <strong class="fs-ticket__nova-resposta">Nova resposta</strong></span>
          </div>
        </div>
      </div>
      <div class="fs-ticket__input-wrapper">
        <textarea class="fs-ticket__input" placeholder="Responder ao ticket..." rows="2"></textarea>
        <button class="fs-ticket__enviar">Enviar</button>
      </div>
    </div>
  </div>

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
      </div>
    </div>
  </template>

  <template id="chat-loading">
    <div class="fs-chat__msg fs-chat__msg--copilot  ">
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