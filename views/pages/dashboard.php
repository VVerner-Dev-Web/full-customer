<?php

use FC\FileSystem;
use FC\SkillRepository;
use FC\User;

$fs = FileSystem::instance();
$repo = SkillRepository::instance();
$user = User::instance();

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
          <input type="text" id="copilotTexto" class="fs-copilot-input__campo" placeholder="" autocomplete=" off" />
          <div class="fs-copilot-input__sugestoes" id="copilotSugestoes"></div>
        </div>

        <!-- Feedback de intent -->
        <div class="fs-copilot-feedback fs-oculto" id="copilotFeedback"></div>

        <!-- Linha 2: chip de skill + Skills + enviar -->
        <div class="fs-cartao-acao__acoes">
          <!-- Chip seletor de skill (esquerda) -->
          <div class="fs-skill-chip" id="skillSeletor">
            <button class="fs-skill-chip__gatilho dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" id="skillGatilho">
              <img src="<?= $fs->getUrl('assets/images/icons/connector-fill.svg') ?>" alt="" width="16" height="16" id="skillIcone" />
              <span id="skillNome">Conectar</span>
              <img src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg') ?>" alt="" width="14" height="14" class="fs-skill-chip__seta" />
            </button>

            <div class="fs-skill-chip__menu dropdown-menu">
              <?php foreach ($repo->all('available') as $skill) : ?>
                <div
                  class="fs-skill-chip__item fs-skill-chip__item--ativo">
                  <div class="fs-skill-chip__item-esq">
                    <img src="<?= $fs->getUrl($skill->getIcon()) ?>" alt="" width="16" height="16" />
                    <div>
                      <span class="fs-skill-chip__item-nome">
                        <?= $skill->getName() ?>
                      </span>
                      <span class="fs-skill-chip__item-desc">
                        <?= $skill->getShortDescription() ?>
                      </span>
                    </div>
                  </div>
                  <span class="fs-skill-chip__check">✓</span>
                </div>
              <?php endforeach; ?>

              <div class="fs-skill-chip__divisor"></div>

              <?php foreach ($repo->all('unavailable') as $skill) : ?>

                <div class="fs-skill-chip__item fs-skill-chip__item--embreve">
                  <div class="fs-skill-chip__item-esq">
                    <img src="<?= $fs->getUrl($skill->getIcon()) ?>" alt="" width="16" height="16" />
                    <div>
                      <span class="fs-skill-chip__item-nome">
                        <?= $skill->getName() ?>
                      </span>
                      <span class="fs-skill-chip__item-desc">
                        <?= $skill->getShortDescription() ?>
                      </span>
                    </div>
                  </div>
                  <span class="fs-emblema fs-emblema--aviso fs-emblema--pequeno">Em breve</span>
                </div>

              <?php endforeach; ?>

            </div>
          </div>

          <!-- direita: Skills + enviar -->
          <div class="fs-cartao-acao__direita">
            <div class="fs-habilidades" id="seletorHabilidades">
              <a href="#" <?= fcElementDataFragments('SkillsFullPage', 'app') ?> class="fs-habilidades__gatilho">
                <img src="<?= $fs->getUrl('assets/images/icons/layers-01.svg') ?>" alt="" width="20" height="20" />
                <span>Skills</span>
              </a>
              <div class="fs-habilidades__menu dropdown-menu">
                <!-- Ativações (ativo) -->
                <a href="#" class="fs-habilidades__item">
                  <div class="fs-habilidades__item-esquerda">
                    <img class="fs-habilidades__item-icone" src="<?= $fs->getUrl('assets/images/icons/skill-energy.svg') ?>" alt="" width="24" height="24" />
                    <div class="fs-habilidades__item-texto">
                      <span class="fs-habilidades__item-nome">Ativações</span>
                      <span class="fs-habilidades__item-desc">Ative plugins PRO no seu site</span>
                    </div>
                  </div>
                  <img class="fs-habilidades__item-seta" src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg') ?>" alt="" width="20" height="20" />
                </a>
                <!-- Builder AI (em breve) -->
                <div class="fs-habilidades__item fs-habilidades__item--desativado">
                  <div class="fs-habilidades__item-esquerda">
                    <img class="fs-habilidades__item-icone" src="<?= $fs->getUrl('assets/images/icons/skill-builder.svg') ?>" alt="" width="24" height="24" />
                    <div class="fs-habilidades__item-texto">
                      <div class="fs-habilidades__item-linha-nome">
                        <span class="fs-habilidades__item-nome">Builder AI</span>
                        <span class="fs-habilidades__emblema">Em breve</span>
                      </div>
                      <span class="fs-habilidades__item-desc">Crie e edite widgets para o seu site</span>
                    </div>
                  </div>
                  <img class="fs-habilidades__item-seta" src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg') ?>" alt="" width="20" height="20" />
                </div>
                <!-- Snippets AI (em breve) -->
                <div class="fs-habilidades__item fs-habilidades__item--desativado">
                  <div class="fs-habilidades__item-esquerda">
                    <img class="fs-habilidades__item-icone" src="<?= $fs->getUrl('assets/images/icons/skill-snippets.svg') ?>" alt="" width="24" height="24" />
                    <div class="fs-habilidades__item-texto">
                      <div class="fs-habilidades__item-linha-nome">
                        <span class="fs-habilidades__item-nome">Snippets AI</span>
                        <span class="fs-habilidades__emblema">Em breve</span>
                      </div>
                      <span class="fs-habilidades__item-desc">Gere e aplique snippets</span>
                    </div>
                  </div>
                  <img class="fs-habilidades__item-seta" src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg') ?>" alt="" width="20" height="20" />
                </div>
                <!-- Error Auto Fix (em breve) -->
                <div class="fs-habilidades__item fs-habilidades__item--desativado">
                  <div class="fs-habilidades__item-esquerda">
                    <img class="fs-habilidades__item-icone" src="<?= $fs->getUrl('assets/images/icons/skill-errorfix.svg') ?>" alt="" width="24" height="24" />
                    <div class="fs-habilidades__item-texto">
                      <div class="fs-habilidades__item-linha-nome">
                        <span class="fs-habilidades__item-nome">Error Auto Fix</span>
                        <span class="fs-habilidades__emblema">Em breve</span>
                      </div>
                      <span class="fs-habilidades__item-desc">Detecte e corrija erros do seu site</span>
                    </div>
                  </div>
                  <img class="fs-habilidades__item-seta" src="<?= $fs->getUrl('assets/images/icons/arrow-down-01.svg') ?>" alt="" width="20" height="20" />
                </div>
              </div>
            </div>
            <button class="fs-btn-enviar" id="btnEnviarCopilot">
              <span class="fs-btn-enviar__label">Ativar</span>
              <img src="<?= $fs->getUrl('assets/images/icons/arrow-right-02.svg') ?>" alt="" width="24" height="24" />
            </button>
          </div>
        </div>
      </div>


      <?php if ($user->isConnected()): ?>
        <!-- Rodapé -->
        <div class="fs-cartao-acao__rodape">
          <div class="fs-cartao-acao__uso">
            <img src="<?= $fs->getUrl('assets/images/icons/progress-ring-mini.svg') ?>" alt="" width="16" height="16" />
            <span>Usando 3 de 14 ativações neste plano</span>
          </div>
          <a href="#" class="fs-cartao-acao__link-plugins">Ver plugins &rarr;</a>
        </div>
      <?php endif; ?>
    </div>

    <?php if ($user->isConnected()): ?>
      <!-- AÇÕES SUGERIDAS -->
      <div class="fs-sugestoes">
        <p class="fs-sugestoes__titulo">Sugestões</p>
        <div class="fs-sugestoes__lista">
          <a href="#" <?= fcElementDataFragments('ChatFullPage', 'app') ?> class="fs-sugestoes__item">
            <img src="<?= $fs->getUrl('assets/images/icons/plugin-elementor.svg') ?>" alt="" width="20" height="20" />
            <span>Ativar Elementor PRO</span>
          </a>
          <a href="#" <?= fcElementDataFragments('ChatFullPage', 'app') ?> class="fs-sugestoes__item">
            <img src="<?= $fs->getUrl('assets/images/icons/plugin-rankmath.svg') ?>" alt="" width="20" height="20" />
            <span>Ativar Rank Math PRO</span>
          </a>
          <a href="#" <?= fcElementDataFragments('ChatFullPage', 'app') ?> class="fs-sugestoes__item">
            <img src="<?= $fs->getUrl('assets/images/icons/plugin-crocoblock.svg') ?>" alt="" width="20" height="20" />
            <span>Ativar Crocoblock</span>
          </a>
          <a href="#" <?= fcElementDataFragments('SkillsFullPage', 'app') ?> class="fs-sugestoes__item">
            <img src="<?= $fs->getUrl('assets/images/icons/layers-01.svg') ?>" alt="" width="20" height="20" />
            <span>Ver todos os plugins</span>
          </a>
        </div>
      </div>
    <?php endif; ?>

  </div>
</main>