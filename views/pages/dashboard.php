<?php

use FC\FileSystem;
use FC\ModelRepository;
use FC\Models\ConnectModel;
use FC\Models\PluginsModel;
use FC\User;

$fs = FileSystem::instance();
$repo = ModelRepository::instance();
$user = User::instance();

$starterSkill = $user->isConnected() ? $repo->get(PluginsModel::ID) : $repo->get(ConnectModel::ID);

?>
<main class="fs-principal">
  <div class="fs-principal__conteudo justify-content-center">
    <!-- Título -->
    <div class="fs-saudacao">
      <div class="fs-saudacao__logo">
        <img src="<?= $fs->getUrl('assets/images/logo-fullservices.svg') ?>" alt="fullservices" height="24" />
      </div>
      <h1 class="fs-saudacao__titulo">Olá! Como posso te ajudar?</h1>

      <!-- Subtítulo -->
      <div class="fs-saudacao__subtitulo">
        <span class="fs-saudacao__ponto">
          <span class="fs-saudacao__ponto-interno"></span>
        </span>
        <p></p>
      </div>
    </div>

    <div class="fs-chat__mensagens" id="fs-copilot-chat"></div>

    <!-- CARTÃO DE AÇÃO -->
    <div class="fs-cartao-acao ">
      <div class="fs-cartao-acao__interno">
        <div class="d-flex gap-3 w-100">
          <div class="d-flex flex-column gap-1">
            <span class="fs-seletor-label">modelo</span>
            <div class="dropdown">
              <button class="fs-skill-chip__gatilho dropdown-toggle" data-bs-toggle="dropdown"
                data-bs-auto-close="outside" id="modeloGatilho">
                <img src="" alt="" width="16" height="16" id="modeloIcone" />
                <span id="modeloNome"></span>
                <img src="<?= $fs->getUrl('assets/images/icons/chevron-down.svg') ?>" alt="" width="14" height="14"
                  class="fs-skill-chip__seta" />
              </button>
              <div class="fs-skill-chip__menu dropdown-menu" id="modeloMenuContainer"></div>
            </div>
          </div>

          <div class="d-flex flex-column gap-1" id="agenteSeletorContainer">
            <span class="fs-seletor-label">agente</span>
            <div class="dropdown">
              <button class="fs-skill-chip__gatilho dropdown-toggle" data-bs-toggle="dropdown"
                data-bs-auto-close="outside" id="agenteGatilho">
                <img src="" alt="" width="16" height="16" id="agenteIcone" />
                <span id="agenteNome"></span>
                <img src="<?= $fs->getUrl('assets/images/icons/chevron-down.svg') ?>" alt="" width="14" height="14"
                  class="fs-skill-chip__seta" />
              </button>
              <div class="fs-skill-chip__menu dropdown-menu" id="agenteMenuContainer"></div>
            </div>
          </div>
        </div>

        <!-- Linha 2: Ação Ativa (Escondida por padrão via d-none) -->
        <div class="fs-acao-ativa d-none align-items-center justify-content-between p-2 mb-1" id="fsAcaoAtivaContainer">
          <div class="d-flex align-items-center gap-2">
            <span class="fs-acao-ativa__tag-label">Ação selecionada:</span>
            <div class="fs-acao-ativa__badge">
              <span class="fw-bold" id="fsAcaoAtivaNome"></span>
            </div>
          </div>
          <button class="fs-acao-ativa__remover" id="fsAcaoAtivaRemover" aria-label="Remover ação">
            <img src="<?= $fs->getUrl('assets/images/icons/close.svg') ?>" alt="" width="12" height="12" />
          </button>
        </div>

        <!-- Linha 3: Input de Texto e Botão de Enviar -->
        <div class="d-flex align-items-center w-100 mt-2">
          <div class="fs-copilot-input flex-grow-1" id="copilotInput">
            <div class="dropdown dropup flex-grow-1">
              <input type="text" id="copilotTexto" class="fs-copilot-input__campo w-100"
                placeholder="Digite sua mensagem ou escolha uma ação..." autocomplete="off"
                data-bs-auto-close="outside" />

              <div class="fs-copilot-input__sugestoes dropdown-menu w-100 shadow-sm mt-1" id="copilotSugestoes"></div>
            </div>
          </div>

          <div class="dropdown dropup ms-2">
            <button class="fs-btn-opcao" type="button" id="btnMenuAcoes" data-bs-toggle="dropdown" aria-expanded="false"
              title="Opções">
              <img src="<?= $fs->getUrl('assets/images/icons/dots-more.svg') ?>" alt="" width="16" height="16" />
            </button>
            <ul class="dropdown-menu dropdown-menu-end fs-menu-dropup" aria-labelledby="btnMenuAcoes">
              <li>
                <button class="dropdown-item d-flex align-items-center gap-2" id="btnRestartChat" type="button">
                  <img src="<?= $fs->getUrl('assets/images/icons/loop-right.svg') ?>" alt="" width="16" height="16" />
                  Reiniciar chat
                </button>
              </li>
              <li>
                <button class="dropdown-item d-flex align-items-center gap-2" id="btnTutorial" type="button">
                  <img src="<?= $fs->getUrl('assets/images/icons/service-bell.svg') ?>" alt="" width="16" height="16" />
                  Ver Tutorial
                </button>
              </li>
              <li>
                <button class="dropdown-item d-flex align-items-center gap-2" type="button" data-bs-toggle="offcanvas"
                  data-bs-target="#sidebarAjuda">
                  <img src="<?= $fs->getUrl('assets/images/icons/help-circle.svg') ?>" alt="" width="16" height="16" />
                  Ajuda e Suporte
                </button>
              </li>
            </ul>
          </div>

          <button class="fs-btn-enviar ms-2" id="btnEnviarCopilot">
            <span class="fs-btn-enviar__label">Executar ação</span>
            <img src="<?= $fs->getUrl('assets/images/icons/arrow-right.svg') ?>" alt="" width="20" height="20" />
          </button>
        </div>
      </div>
    </div>

  </div>
</main>