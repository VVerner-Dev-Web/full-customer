<?php $endpoint = filter_input(INPUT_GET, 'endpoint') ? filter_input(INPUT_GET, 'endpoint') : 'pages'; ?>
<div class="full-templates-admin-body">
  <div class="templately-wrapper">
    <?php require __DIR__ . '/templates/header.php' ?>

    <div class="templately-container templately-pages-container">
      <div class="templately-container-row">
        <?php require __DIR__ . '/templates/endpoints/' . $endpoint . '.php' ?>
      </div>
    </div>
  </div>
</div>