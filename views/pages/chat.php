<?php

use FC\FileSystem;

$fs = FileSystem::instance();

?>

<main class="fs-chat">
  <div class="fs-chat__container">
    <!-- MENSAGENS -->
    <div class="fs-chat__mensagens" id="chatMensagens">
      <!-- Mensagem do usuário -->
      <div class="fs-chat__msg fs-chat__msg--usuario fs-chat__animavel">
        <div class="fs-chat__balao">
          <p>Ative o plugin Elementor PRO no meu site</p>
        </div>
      </div>

      <!-- Resposta do Copilot -->
      <div class="fs-chat__msg fs-chat__msg--copilot fs-chat__animavel">
        <div class="fs-chat__autor">
          <span class="fs-chat__autor-icone">
            <img src="<?= $fs->getUrl('assets/images/icons/smiley.svg'); ?>" alt="" width="12" height="10" />
          </span>
          <span class="fs-chat__autor-nome">Copilot</span>
        </div>
        <p class="fs-chat__texto">O plugin Elementor PRO não foi encontrado no seu WordPress. Vou realizar a instalação a partir do repositório oficial antes de ativá-lo.</p>
      </div>

      <!-- Cartão de progresso -->
      <div class="fs-chat__msg fs-chat__msg--copilot fs-chat__animavel">
        <div class="fs-chat__cartao-progresso">
          <div class="fs-chat__progresso-cabecalho">
            <img src="<?= $fs->getUrl('assets/images/icons/plugin-elementor.svg'); ?>" alt="Elementor Pro" width="24" height="24" />
            <span class="fs-chat__progresso-titulo">Elementor Pro</span>
            <img class="fs-chat__progresso-seta fs-chat__progresso-seta--colapsado" src="<?= $fs->getUrl('assets/images/icons/chevron-right.svg'); ?>" alt="" width="20" height="20" />
          </div>
          <div class="fs-chat__progresso-divisor"></div>
          <div class="fs-chat__progresso-passos">
            <div class="fs-chat__passo fs-chat__passo-grupo fs-chat__passo--concluido">
              <img src="<?= $fs->getUrl('assets/images/icons/check-circle-green.svg'); ?>" alt="" width="16" height="16" />
              <span class="fs-chat__passo-texto">Baixando plugin</span>
              <span class="fs-chat__passo-status fs-chat__passo-status--sucesso">Concluído</span>
            </div>
            <div class="fs-chat__passo-conector fs-chat__passo-grupo"></div>
            <div class="fs-chat__passo fs-chat__passo-grupo fs-chat__passo--concluido">
              <img src="<?= $fs->getUrl('assets/images/icons/check-circle-green.svg'); ?>" alt="" width="16" height="16" />
              <span class="fs-chat__passo-texto">Instalando plugin</span>
              <span class="fs-chat__passo-status fs-chat__passo-status--sucesso">Concluído</span>
            </div>
            <div class="fs-chat__passo-conector fs-chat__passo-grupo"></div>
            <div class="fs-chat__passo fs-chat__passo-grupo fs-chat__passo--concluido">
              <img src="<?= $fs->getUrl('assets/images/icons/check-circle-green.svg'); ?>" alt="" width="16" height="16" />
              <span class="fs-chat__passo-texto">Ativando licença</span>
              <span class="fs-chat__passo-status fs-chat__passo-status--sucesso">Concluído</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Mensagem de conclusão do Copilot -->
      <div class="fs-chat__msg fs-chat__msg--copilot fs-chat__animavel">
        <div class="fs-chat__autor">
          <span class="fs-chat__autor-icone">
            <img src="<?= $fs->getUrl('assets/images/icons/smiley.svg'); ?>" alt="" width="12" height="10" />
          </span>
          <span class="fs-chat__autor-nome">Copilot</span>
        </div>
        <p class="fs-chat__texto">Pronto! Elementor PRO está ativo no seu ambiente. Esta ativação foi registrada no seu assento FULL &mdash; sem chave de licença, sem configuração manual.</p>
      </div>

      <!-- Cartão informativo -->
      <div class="fs-chat__msg fs-chat__msg--copilot fs-chat__animavel">
        <div class="fs-chat__cartao-info">
          <div class="fs-chat__info-cabecalho">
            <img src="<?= $fs->getUrl('assets/images/icons/energy-amber-chat.svg'); ?>" alt="" width="20" height="20" />
            <div class="fs-chat__info-conteudo">
              <p class="fs-chat__info-titulo">10 ativações disponíveis para este plugin</p>
              <p class="fs-chat__info-desc">
                Distribua entre qualquer um dos seus sites. No plano PRO você tem 10 ativações por cada um dos 14 plugins da stack. <a href="#" class="fs-chat__info-link">Ver planos &rarr;</a>
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Banner de indicação -->
      <div class="fs-chat__msg fs-chat__msg--copilot fs-chat__animavel">
        <div class="fs-chat__referral">
          <div class="fs-chat__referral-texto">
            <img src="<?= $fs->getUrl('assets/images/icons/referral-icon.svg'); ?>" alt="" width="20" height="16" />
            <p>Gostou das ativações? Indique a FULL e ganhe 20% de comissão recorrente por cada cliente.</p>
          </div>
          <button class="fs-chat__referral-btn">
            <img src="<?= $fs->getUrl('assets/images/icons/copy-chat.svg'); ?>" alt="" width="16" height="16" />
            <span>Copiar meu link</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Input Card -->
  <div class="fs-chat__entrada-fixo">
    <div class="fs-chat__entrada">
      <p class="fs-chat__entrada-texto">Quer ativar outro plugin?</p>
      <div class="fs-chat__entrada-acoes">
        <div class="fs-seletor" id="seletorAtivacaoChat">
          <button class="fs-seletor__gatilho dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
            <img src="<?= $fs->getUrl('assets/images/icons/energy.svg'); ?>" alt="" width="20" height="20" />
            <span>Nova ativação</span>
            <img class="fs-seletor__seta" src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg'); ?>" alt="" width="16" height="16" />
          </button>
          <div class="fs-seletor__menu dropdown-menu">
            <input type="text" class="fs-seletor__campo-busca" placeholder="Buscar plugin..." />
            <div class="fs-seletor__itens">
              <a href="#" class="fs-seletor__item">
                <img class="fs-seletor__item-icone" src="<?= $fs->getUrl('assets/images/icons/plugin-elementor.svg'); ?>" alt="" />
                <div class="fs-seletor__item-texto">
                  <span class="fs-seletor__item-nome">Elementor PRO</span>
                  <span class="fs-seletor__item-desc">Page builder premium</span>
                </div>
              </a>
              <a href="#" class="fs-seletor__item">
                <img class="fs-seletor__item-icone" src="<?= $fs->getUrl('assets/images/icons/plugin-crocoblock.svg'); ?>" alt="" />
                <div class="fs-seletor__item-texto">
                  <span class="fs-seletor__item-nome">Crocoblock</span>
                  <span class="fs-seletor__item-desc">Suite de plugins JetEngine</span>
                </div>
              </a>
              <a href="#" class="fs-seletor__item">
                <img class="fs-seletor__item-icone" src="<?= $fs->getUrl('assets/images/icons/plugin-astra.svg'); ?>" alt="" />
                <div class="fs-seletor__item-texto">
                  <span class="fs-seletor__item-nome">Astra PRO</span>
                  <span class="fs-seletor__item-desc">Tema premium WordPress</span>
                </div>
              </a>
              <a href="#" class="fs-seletor__item">
                <img class="fs-seletor__item-icone" src="<?= $fs->getUrl('assets/images/icons/plugin-wprocket.svg'); ?>" alt="" />
                <div class="fs-seletor__item-texto">
                  <span class="fs-seletor__item-nome">WP Rocket</span>
                  <span class="fs-seletor__item-desc">Plugin de cache e performance</span>
                </div>
              </a>
              <a href="#" class="fs-seletor__item">
                <img class="fs-seletor__item-icone" src="<?= $fs->getUrl('assets/images/icons/plugin-acf.svg'); ?>" alt="" />
                <div class="fs-seletor__item-texto">
                  <span class="fs-seletor__item-nome">ACF PRO</span>
                  <span class="fs-seletor__item-desc">Custom fields avançados</span>
                </div>
              </a>
            </div>
          </div>
        </div>
        <button class="fs-btn-enviar" id="btnEnviar">
          <img src="<?= $fs->getUrl('assets/images/icons/arrow-right-02.svg'); ?>" alt="Enviar" width="24" height="24" />
        </button>
      </div>
    </div>
  </div>
</main>