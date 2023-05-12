<?php

if (!isset($endpointView)) :
  $endpointView = filter_input(INPUT_GET, 'endpoint') ? filter_input(INPUT_GET, 'endpoint') : 'templates';
endif;

?>
<div class="full-templates-admin-body">
  <div class="templately-wrapper">
    <?php require __DIR__ . '/templates/header.php' ?>

    <div class="templately-container templately-pages-container">
      <div class="templately-container-row" id="endpoint-viewport">
        <?php require __DIR__ . '/templates/endpoints/' . $endpointView . '.php' ?>
      </div>
    </div>
  </div>
</div>