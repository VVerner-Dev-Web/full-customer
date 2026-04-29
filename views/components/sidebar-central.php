<?php

use FC\FileSystem;

$fs = FileSystem::instance();

?>

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