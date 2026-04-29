<?php

use FC\FileSystem;
use FC\User;
use FC\SkillRepository;

$fs = FileSystem::instance();
$user = User::instance();
$skill = SkillRepository::instance()->get('connect');
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
              <span>Novas skills desbloqueadas</span>
            </li>
            <li class="fs-aba-embreve__item">
              <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
              <span>Consultar assentos disponíveis</span>
            </li>
            <li class="fs-aba-embreve__item">
              <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
              <span>Acesso rápido para solicitar novas ativações</span>
            </li>
            <li class="fs-aba-embreve__item">
              <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
              <span>Novos recursos incríveis</span>
            </li>
          </ul>

          <a <?= fcElementDataFragments('DashboardFullPage', 'app') ?> class="fs-aba-embreve__cta">
            Realizar conexão &nbsp;&rarr;
          </a>
        </div>
      </div>

    <?php else: ?>

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
              <a <?= fcElementDataFragments('DashboardFullPage', 'app', [
                    'prompt' => $action->getPrompt()
                  ]) ?> class="fs-btn-ativar">
                <img src="<?= $fs->getUrl('assets/images/icons/energy-dark.svg') ?>" alt="" width="18" height="18" />
                <?= $action->getCta() ?>
              </a>
            </div>
          </div>
        <?php endforeach; ?>

      </div>

    <?php endif; ?>

  </div>
</div>