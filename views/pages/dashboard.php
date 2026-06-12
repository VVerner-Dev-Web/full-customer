<?php

use FC\FileSystem;
use FC\SkillRepository;
use FC\User;

$fs = FileSystem::instance();
$repo = SkillRepository::instance();
$user = User::instance();

$starterSkill = $user->isConnected() ? $repo->get('activateProPlugin') : $repo->get('connect');

?>
<main class="fs-principal">
  <div class="fs-principal__conteudo">
    <!-- Título -->
    <div class="fs-saudacao">
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
        <!-- Linha 1: Input com tags multi-select -->
        <div class="fs-copilot-input" id="copilotInput">
          <!-- Tags de plugins selecionados -->
          <div class="fs-copilot-tags" id="copilotTags"></div>
          <!-- Campo de texto -->
          <input type="text" id="copilotTexto" class="fs-copilot-input__campo" placeholder="" autocomplete="off" />
          <div class="fs-copilot-input__sugestoes" id="copilotSugestoes"></div>
        </div>

        <!-- Feedback de intent -->
        <div class="fs-copilot-feedback fs-oculto" id="copilotFeedback"></div>

        <!-- Linha 2: chip de skill + Skills + enviar -->
        <div class="fs-cartao-acao__acoes">
          <!-- Chip seletor de skill (esquerda) -->
          <div class="fs-skill-chip" id="skillSeletor">
            <button class="fs-skill-chip__gatilho dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" id="skillGatilho">
              <img src="" alt="" width="16" height="16" id="skillIcone" />
              <span id="skillNome"></span>
              <img src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg') ?>" alt="" width="14" height="14" class="fs-skill-chip__seta" />
            </button>

            <div class="fs-skill-chip__menu dropdown-menu" id="skillMenuContainer"></div>
          </div>

          <!-- direita: Skills + enviar -->
          <div class="fs-cartao-acao__direita">
            <div class="fs-habilidades" id="seletorHabilidades">
              <a href="#" <?= fcElementDataFragments('SkillsFullPage', 'app') ?> class="fs-habilidades__gatilho">
                <img src="<?= $fs->getUrl('assets/images/icons/layers-01.svg') ?>" alt="" width="20" height="20" />
                <span>Skills</span>
              </a>
              <div class="fs-habilidades__menu dropdown-menu">
              </div>
            </div>
            <button class="fs-btn-enviar" id="btnEnviarCopilot">
              <span class="fs-btn-enviar__label">Executar ação</span>
              <img src="<?= $fs->getUrl('assets/images/icons/arrow-right-02.svg') ?>" alt="" width="24" height="24" />
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- AÇÕES SUGERIDAS -->
    <div class="fs-sugestoes d-none">
      <template>
        <a href="#" class="fs-sugestoes__item">
          <img src="" alt="" width="20" height="20" />
          <span></span>
        </a>
      </template>
      <p class="fs-sugestoes__titulo">Sugestões</p>
      <div class="fs-sugestoes__lista">
      </div>
    </div>

  </div>
</main>