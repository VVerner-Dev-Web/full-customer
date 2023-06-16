<?php $quota = get_option('full/ai/quota', null) ?>

<div id="prompt-header">
  <div class="ai-logo prompt-header-item">
    <img src="<?= fullGetImageUrl('logo-full-ai.png') ?>" alt="Logo FULL">
  </div>
  <div class="ai-menu prompt-header-item">
    <div class="active">
      Conteúdo AI
    </div>
    <div>
      Website Builder AI <small>(em breve)</small>
    </div>
  </div>
  <div class="ai-usage prompt-header-item">
    <div class="progress-legend">
      Uso de palavras <span data-quota="used"><?= $quota ? $quota->used : '0' ?></span> de <span data-quota="granted"><?= $quota ? $quota->granted : '0' ?></span>
    </div>
    <div class="progress-bar">
      <span class="progress" style="width: <?= $quota ? ($quota->used / $quota->granted) * 100 : 0 ?>%"></span>
    </div>
  </div>
</div>

<div id="prompt-container">
  <div id="prompt-templates">
    <!-- JS -->
  </div>

  <div id="prompt-response">
    <div id="prompt-response-content">
      <!-- JS -->
    </div>

    <div id="prompt-actions">
      <span class="prompt-button" data-action="redo">
        <i class="eicon-redo"></i> Refazer
      </span>
      <span class="prompt-button" data-action="copy">
        <i class="eicon-copy"></i> Copiar
      </span>
      <span class="prompt-button" data-action="insert">
        <i class="eicon-download-button"></i> Inserir no Elementor
      </span>
    </div>
  </div>

  <div id="prompt-alert">
    <!-- JS -->
  </div>

  <div id="prompt-loader">
    <!-- JS -->
  </div>

</div>

<div id="prompt-message">
  <div class="ai-mode">
    <strong>Modo atual: </strong>
    <span>Subtítulo para site</span>
    <a href="#">Alterar</a>
  </div>
  <div class="input-container">
    <textarea placeholder="Envie uma descrição detalhada do que você precisa"></textarea>
    <button>
      Gerar texto
      <i class="eicon-pencil"></i>
    </button>
  </div>
</div>

_SCRIPTS_DIVIDER_

<script type="text/template" id="full-ai-prompt-template">
  <div class="single-prompt-template" data-template='{json}'>
    <h3>{title}</h3>
    <p>{content}</p>
  </div>
</script>