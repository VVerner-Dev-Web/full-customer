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
      </div>

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