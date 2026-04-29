<?php

use FC\FileSystem;
use FC\User;
use FC\SkillRepository;

$fs = FileSystem::instance();
$user = User::instance();
$skill = SkillRepository::instance()->get('activateProPlugin');
?>

<div class="fs-habilidades-grade">
  <div class="fs-habilidades-grade__container">

    <?php if (!$user->isConnected()) : ?>

      <div class="fs-aba-embreve fs-animar">
        <div class="fs-aba-embreve__cabecalho">
          <div class="fs-aba-embreve__icone">
            <img src="<?= $fs->getUrl($skill->getIcon()) ?>" alt="<?= $skill->getName() ?>" width="24" height="24" />
          </div>
          <h3 class="fs-aba-embreve__titulo">
            <?= $skill->getName() ?>
          </h3>
        </div>
        <p class="fs-aba-embreve__desc">
          <?= $skill->getDescription() ?>
        </p>
        <div class="fs-aba-embreve__cartao">
          <ul class="fs-aba-embreve__lista">
            <li class="fs-aba-embreve__item">
              <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
              <span>Consultar assentos disponíveis</span>
            </li>
            <li class="fs-aba-embreve__item">
              <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
              <span>Solicitar novas ativações</span>
            </li>
            <li class="fs-aba-embreve__item">
              <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
              <span>Consultar status de ativações</span>
            </li>
            <li class="fs-aba-embreve__item">
              <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
              <span>Recursos PRO disponíveis</span>
            </li>
          </ul>

          <a <?= fcElementDataFragments('DashboardFullPage', 'app') ?> class="fs-aba-embreve__cta">
            Realizar conexão &nbsp;&rarr;
          </a>
        </div>
      </div>

    <?php else: ?>

      <div class="fs-habilidades-grade__linha">
        <!-- Crocoblock - Ativo -->
        <div class="fs-cartao-plugin">
          <div class="fs-cartao-plugin__info">
            <img class="fs-cartao-plugin__logo" src="<?= $fs->getUrl('assets/images/icons/plugin-crocoblock.svg') ?>" alt="Crocoblock" />
            <div class="fs-cartao-plugin__detalhes">
              <div class="fs-cartao-plugin__titulo-linha">
                <h3 class="fs-cartao-plugin__titulo" data-plugin-nome="crocoblock" data-plugin-tags="crocoblock jet jetengine">Crocoblock</h3>
                <span class="fs-cartao-plugin__titulo-sep"></span>
                <button class="fs-cartao-plugin__addons-btn" data-bs-toggle="modal" data-bs-target="#modalAddons">
                  <img src="<?= $fs->getUrl('assets/images/icons/dashboard-square-add.svg') ?>" alt="" width="16" height="16" />
                  Addons
                </button>
              </div>
              <p class="fs-cartao-plugin__desc">Use essa extensão para disponibilizar o Crocoblock e os seus plugins em seu site.</p>
            </div>
          </div>
          <div class="fs-cartao-plugin__progresso">
            <div class="fs-cartao-plugin__barra-progresso">
              <div class="fs-cartao-plugin__barra-preenchimento" style="width: 70%"></div>
            </div>
            <span class="fs-cartao-plugin__texto-progresso">7/10 Sites</span>
          </div>
          <div class="fs-cartao-plugin__divisor"></div>
          <div class="fs-cartao-plugin__rodape">
            <button class="fs-acao-secundaria" title="Mais opções">
              <img src="<?= $fs->getUrl('assets/images/icons/square-lock.svg') ?>" alt="" width="14" height="14" />
              <span>Opções</span>
            </button>
            <span class="fs-emblema fs-emblema--sucesso">
              <span class="fs-emblema__ponto"></span>
              Ativo
            </span>
          </div>
        </div>

        <!-- Elementor PRO - Ativar -->
        <div class="fs-cartao-plugin">
          <div class="fs-cartao-plugin__info">
            <img class="fs-cartao-plugin__logo" src="<?= $fs->getUrl('assets/images/icons/plugin-elementor-circle.svg') ?>" alt="Elementor PRO" />
            <div class="fs-cartao-plugin__detalhes">
              <h3 class="fs-cartao-plugin__titulo" data-plugin-nome="elementor pro" data-plugin-tags="elementor pro page builder">Elementor PRO</h3>
              <p class="fs-cartao-plugin__desc">Use essa extensão para disponibilizar o Elementor PRO em seu site.</p>
            </div>
          </div>
          <div class="fs-cartao-plugin__progresso">
            <div class="fs-cartao-plugin__barra-progresso">
              <div class="fs-cartao-plugin__barra-preenchimento" style="width: 0%"></div>
            </div>
            <span class="fs-cartao-plugin__texto-progresso">0/10 Sites</span>
          </div>
          <div class="fs-cartao-plugin__divisor"></div>
          <div class="fs-cartao-plugin__rodape">
            <button class="fs-acao-bloqueio">
              <img src="<?= $fs->getUrl('assets/images/icons/square-lock.svg') ?>" alt="" width="14" height="14" />
              Bloquear
            </button>
            <a href="chat.html" class="fs-btn-ativar">
              <img src="<?= $fs->getUrl('assets/images/icons/energy-dark.svg') ?>" alt="" width="18" height="18" />
              Ativar
            </a>
          </div>
        </div>

        <!-- WP Rocket - Bloqueado -->
        <div class="fs-cartao-plugin fs-cartao-plugin--bloqueado">
          <div class="fs-cartao-plugin__info">
            <img class="fs-cartao-plugin__logo" src="<?= $fs->getUrl('assets/images/icons/plugin-wprocket.svg') ?>" alt="WP Rocket" />
            <div class="fs-cartao-plugin__detalhes">
              <h3 class="fs-cartao-plugin__titulo" data-plugin-nome="wp rocket" data-plugin-tags="wp rocket cache velocidade performance">WP Rocket</h3>
              <p class="fs-cartao-plugin__desc">Use essa extensão para disponibilizar o WP Rocket em seu site.</p>
            </div>
          </div>
          <div class="fs-cartao-plugin__progresso">
            <div class="fs-cartao-plugin__barra-progresso">
              <div class="fs-cartao-plugin__barra-preenchimento" style="width: 0%"></div>
            </div>
            <span class="fs-cartao-plugin__texto-progresso">0/10 Sites</span>
          </div>
          <div class="fs-cartao-plugin__divisor"></div>
          <div class="fs-cartao-plugin__rodape">
            <button class="fs-acao-bloqueio fs-acao-bloqueio--desbloquear">
              <img src="<?= $fs->getUrl('assets/images/icons/square-unlock.svg') ?>" alt="" width="14" height="14" />
              Desbloquear
            </button>
            <span class="fs-emblema fs-emblema--perigo">
              <span class="fs-emblema__ponto"></span>
              Bloqueado
            </span>
          </div>
        </div>
      </div>

      <!-- Sem resultados de busca -->
      <div class="fs-skills-sem-resultado" id="skillsSemResultado">
        <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
          <circle cx="18" cy="18" r="13" stroke="#E9ECF0" stroke-width="2" />
          <path d="M28 28L36 36" stroke="#E9ECF0" stroke-width="2.5" stroke-linecap="round" />
        </svg>
        <p class="fs-skills-sem-resultado__titulo">Nenhum plugin encontrado</p>
        <p class="fs-skills-sem-resultado__desc" id="skillsSemResultadoDesc"></p>
      </div>

      <!-- Banner CTA -->
      <div class="fs-cta-destaque fs-animar">
        <div class="fs-cta-destaque__conteudo">
          <div class="fs-cta-destaque__icone">
            <img src="<?= $fs->getUrl('assets/images/icons/crown-white.svg') ?>" alt="" width="32" height="32" />
          </div>
          <div class="fs-cta-destaque__texto">
            <p class="fs-cta-destaque__titulo">Ative todas as extensões em mais sites com o plano PRO</p>
            <p class="fs-cta-destaque__desc">Todos os planos dão acesso às mesmas ativações da pilha. O que muda é quantos assentos (sites) você gerencia.</p>
          </div>
        </div>
        <a href="https://full.services/?utm_source=fc" class="fs-cta-destaque__btn" target="_blank" rel="noopener noreferrer">
          Ver planos
        </a>
      </div>

    <?php endif; ?>

  </div>
</div>