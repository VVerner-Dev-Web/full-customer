<?php

use FC\FileSystem;
use FC\SkillRepository;

$fs = FileSystem::instance();
$skill = SkillRepository::instance()->get('errorFix');

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
            <span>Diagnóstico automático em tempo real</span>
          </li>
          <li class="fs-aba-embreve__item">
            <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
            <span>Correção com um clique</span>
          </li>
          <li class="fs-aba-embreve__item">
            <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
            <span>Biblioteca de 200+ erros conhecidos</span>
          </li>
          <li class="fs-aba-embreve__item">
            <img src="<?= $fs->getUrl('assets/images/icons/checkmark-circle.svg') ?>" alt="" width="18" height="18" />
            <span>Reversão segura se a correção falhar</span>
          </li>
        </ul>
        <a href="#" class="fs-aba-embreve__cta">Garantir meu lugar na fila &nbsp;&rarr;</a>
        <span class="fs-emblema fs-emblema--aviso">🔥 247 pessoas na fila de acesso antecipado</span>
      </div>
    </div>
  </div>
</div>