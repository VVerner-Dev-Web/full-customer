<?php

use FC\FileSystem;
use FC\SkillRepository;
use FC\User;

$fs = FileSystem::instance();
$repo = SkillRepository::instance();
$user = User::instance();
$starterSkill = $user->isConnected() ? $repo->get('activateProPlugin') : $repo->get('connect');

?>

<div class="fs-habilidades-topo">
  <div class="fs-habilidades-topo__fundo"></div>
  <div class="fs-habilidades-topo__conteudo">

    <div class="fs-habilidades-titulo fs-animar">
      <a <?= fcElementDataFragments('DashboardFullPage', 'app') ?> class="fs-link-voltar">
        <img src="<?= $fs->getUrl('assets/images/icons/arrow-left.svg') ?>" alt="" width="16" height="16" />
        Copilot
      </a>
      <div class="fs-habilidades-titulo__cabecalho">
        <img src="<?= $fs->getUrl('assets/images/icons/layers-01.svg') ?>" alt="" width="24" height="24" />
        <h1>Skills</h1>
      </div>
      <p class="fs-habilidades-titulo__desc">
        Skills são as capacidades do seu Copilot. Com o modelo de ativações da FULL, cada assento dá acesso a toda a pilha — você ativa o que quiser, quando quiser, sem pagar por ativação
        individual.
      </p>
    </div>

    <div class="fs-habilidades-abas-wrapper fs-animar fs-animar--d1">
      <button class="fs-habilidades-abas-wrapper__seta fs-habilidades-abas-wrapper__seta--esq fs-oculto" id="abaSetaEsq" aria-label="Rolar abas para a esquerda">
        <img src="<?= $fs->getUrl('assets/images/icons/chevron-left.svg') ?>" alt="" width="16" height="16" />
      </button>

      <ul class="fs-habilidades-abas nav" id="abasHabilidades" role="tablist">
        <?php foreach ($repo->all() as $skill) : ?>
          <li class="nav-item" role="presentation">
            <button
              class="fs-habilidades-abas__item <?= $skill === $starterSkill ? 'active' : ''; ?>"
              id="tab-<?= $skill::ID ?>"
              data-bs-toggle="tab"
              data-bs-target="#pane-<?= $skill::ID ?>"
              type="button"
              role="tab">
              <img src="<?= $fs->getUrl($skill->getIcon()) ?>" alt="<?= $skill->getName() ?>" width="20" height="20" />
              <?= $skill->getName() ?>
              <?php if ($skill->isSoon()) : ?>
                <span class="fs-emblema fs-emblema--aviso">Em breve</span>
              <?php elseif (!$skill->isAvailable()) : ?>
                <span class="fs-emblema fs-emblema--aviso">Bloqueado</span>
              <?php endif; ?>
            </button>
          </li>
        <?php endforeach; ?>
      </ul>

      <button class="fs-habilidades-abas-wrapper__seta fs-habilidades-abas-wrapper__seta--dir fs-oculto" id="abaSetaDir" aria-label="Rolar abas para a direita">
        <img src="<?= $fs->getUrl('assets/images/icons/chevron-right.svg') ?>" alt="" width="16" height="16" />
      </button>
    </div>
  </div>
</div>

<!-- CONTEÚDO DAS ABAS -->
<main class="tab-content" id="conteudoAbas">
  <?php foreach ($repo->all() as $skill) : ?>
    <div class="tab-pane fade show <?= $skill === $starterSkill ? 'active' : ''; ?>" id="pane-<?= $skill::ID ?>" role="tabpanel" aria-labelledby="tab-<?= $skill::ID ?>">
      <div class="fs-habilidades-grade">
        <div class="fs-habilidades-grade__container">

          <?php if ($skill->isAvailable()): ?>
            <div class="fs-habilidades-grade__linha">

              <?php foreach ($skill->actions() as $action): ?>
                <div class="fs-cartao-plugin <?= $action->isAvailable() ? '' : 'fs-cartao-plugin--bloqueado' ?>">
                  <div class="fs-cartao-plugin__info">
                    <div class="fs-aba-embreve__icone">
                      <img src="<?= $fs->getUrl($action->getIcon()) ?>" alt="<?= $action->getName() ?>" width="24" height="24">
                    </div>
                    <div class="fs-cartao-plugin__detalhes">
                      <h3 class="fs-cartao-plugin__titulo">
                        <?= $action->getName() ?>
                      </h3>
                      <p class="fs-cartao-plugin__desc">
                        <?= $action->getShortDescription() ?>
                      </p>
                    </div>
                  </div>
                  <div class="fs-cartao-plugin__divisor"></div>
                  <div class="fs-cartao-plugin__rodape">
                    <?php if (!$action->isAvailable()): ?>
                      <button class="fs-acao-bloqueio">
                        <img src="<?= $fs->getUrl('assets/images/icons/square-lock.svg') ?>" alt="" width="14" height="14" />
                        Bloqueado
                      </button>
                    <?php endif; ?>
                    <a class="fs-btn-ativar disabled">
                      <img src="<?= $fs->getUrl('assets/images/icons/energy-dark.svg') ?>" alt="" width="18" height="18" />
                      <?= $action->getCta() ?>
                    </a>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

          <?php else: ?>
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
                  <?php foreach ($skill->getFeaturesList() as $feature) : ?>
                    <li class="fs-aba-embreve__item">
                      <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
                      <span><?= $feature ?></span>
                    </li>
                  <?php endforeach; ?>
                </ul>

                <?php if ($skill->isSoon()): ?>
                  <a href="#" class="fs-aba-embreve__cta">Garantir meu lugar na fila &nbsp;&rarr;</a>
                  <span class="fs-emblema fs-emblema--aviso">🔥 247 pessoas na fila de acesso antecipado</span>
                <?php else: ?>
                  <a <?= fcElementDataFragments('DashboardFullPage', 'app') ?> class="fs-aba-embreve__cta">
                    Realizar conexão &nbsp;&rarr;
                  </a>
                <?php endif; ?>
              </div>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  <?php endforeach; ?>
</main>