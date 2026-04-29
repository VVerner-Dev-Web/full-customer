<?php

use FC\FileSystem;
use FC\SkillRepository;

$fs = FileSystem::instance();
$skill = SkillRepository::instance()->get('snippet');

?>

<div class="fs-habilidades-grade">
  <div class="fs-habilidades-grade__container">
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
            <span>Geração por prompt em português</span>
          </li>
          <li class="fs-aba-embreve__item">
            <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
            <span>Biblioteca de trechos prontos</span>
          </li>
          <li class="fs-aba-embreve__item">
            <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
            <span>Instalação com reversão automática</span>
          </li>
          <li class="fs-aba-embreve__item">
            <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
            <span>Versionamento e histórico</span>
          </li>
        </ul>
        <a href="#" class="fs-aba-embreve__cta">Garantir meu lugar na fila &nbsp;&rarr;</a>
        <span class="fs-emblema fs-emblema--aviso">🔥 247 pessoas na fila de acesso antecipado</span>
      </div>
    </div>
  </div>
</div>