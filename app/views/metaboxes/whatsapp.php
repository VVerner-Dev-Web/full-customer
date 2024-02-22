<?php

use Full\Customer\WhatsApp\Settings;

$worker = new Settings();
$settings = $worker->getSinglePostSettings(get_the_ID());
?>


<input type="hidden" name="fullUpdatingWhatsApp" value="1">

<div>
  <label for="full-whatsappDisplay">Exibir botão de WhatsApp?</label><br>
  <select style="display:block; width: 100%" name="full[whatsappDisplay]" id="full-whatsappDisplay">
    <option <?php selected($settings->display, 'inherit') ?> value="inherit">Configuração padrão</option>
    <option <?php selected($settings->display, 'yes') ?> value="yes">Exibir</option>
    <option <?php selected($settings->display, 'no') ?> value="no">Não exibir</option>
  </select>
</div>

<br>

<div>
  <label for="full-whatsappNumber">Número de WhatsApp personalizado</label><br>
  <input style="display:block; width: 100%" type="text" name="full[whatsappNumber]" id="full-whatsappNumber" value="<?= $settings->number ?>" placeholder="(00) 98765-4321">
</div>

<br>

<div>
  <label for="full-whatsappMessage">Mensagem personalizada para envio</label>
  <input style="display:block; width: 100%" type="text" name="full[whatsappMessage]" id="full-whatsappMessage" value="<?= $settings->message ?>">
</div>

<br>

<strong>Dica:</strong> Deixe os campos de número e mensagem em branco para utilizar as configurações padrões definidas.