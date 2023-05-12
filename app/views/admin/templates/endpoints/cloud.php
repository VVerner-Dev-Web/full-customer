<?php $section = filter_input(INPUT_GET, 'section') ? filter_input(INPUT_GET, 'section') : 'cloud'; ?>

<div class="templately-sidebar templately-clouds-sidebar">
  <div class="templately-nav-wrapper templately-clouds-menu templately-nav-sidebar">
    <ul class="">
      <li class="tn-item nav-item-clouds <?= 'cloud' === $section  ? 'nav-item-active' : '' ?>">
        <a href="<?= add_query_arg(['section' => 'cloud']) ?>">
          <i class="tio-cloud-outlined"></i>
          Meu Cloud
        </a>
      </li>
      <li class="tn-item nav-item-clouds">
        <a href="#!" data-js="sync-cloud-template">
          <i class="tio-sync"></i>
          Sincronizar cloud
        </a>
      </li>
    </ul>
  </div>

  <div class="templately-clouds-size">
    <a>
      <p>Status do Cloud</p>
      <p>Operacional</p>
    </a>
  </div>
</div>

<div class="templately-contents">
  <?php require_once FULL_CUSTOMER_APP . '/views/admin/templates/cloud/' . $section . '.php'; ?>
</div>

<?php if (isset($templateAsScript)) : ?>
  _SCRIPTS_DIVIDER_
<?php endif; ?>

<script type="text/template" id="tpl-templately-cloud-item">
  <div class="templately-table-row single-cloud-item">
    <div class="templately-table-column ">
      <div class="templatey-cloud-header">
        <p>
          {title}
          <br>
          <small style="opacity: .5;">{slug}</small>
        </p>
      </div>
    </div>
    <div class="templately-table-column ">
      <div class="templately-table-row ">
        <div class="templately-table-column ">
          <p>{formattedDate}</p>
        </div>
        <div class="templately-table-column ">
          <div class="cloud-segment">
            <button class="cloud-button" title="Inserir template" data-js="insert-item" data-item='{json}'>
              <i class="tio-download-from-cloud"></i>
            </button>
            <button class="cloud-button" title="Excluir template" data-js="delete-from-cloud" data-item='{json}'>
              <i class="tio-delete-outlined"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</script>