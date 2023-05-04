<div class="templately-contents-header ">
  <div class="templately-contents-header-inner">
    <div class="templately-header-title">
      <h3>Meu Cloud</h3>
    </div>
  </div>
</div>
<div class="templately-my-clouds">
  <div class="templately-table tt-view-list">
    <div class="templately-table-row templately-table-head">
      <div class="templately-table-column ">
        <p>Nome</p>
      </div>
      <div class="templately-table-column ">
        <div class="templately-table-row ">
          <div class="templately-table-column ">
            <p>Data de criação</p>
          </div>
          <div class="templately-table-column ">
            <p>Ações</p>
          </div>
        </div>
      </div>
    </div>
    <div class="templately-table-body tt-view-list" id="response-container" data-page="1" data-type="cloud">
      <!-- JS -->
    </div>

    <div class="templately-my-clouds templately-has-no-items" id="no-items">
      <div class="templately-no-items">
        <div class="templately-no-items-inner">
          <img src="<?php echo esc_url(fullGetImageUrl('sorry.svg')) ?>" alt="" style="max-width: min(10rem, 80%);">
          <div>
            <h3>Ops, nada encontrado</h3>
            <p>Para enviar seu primeiro modelo para o cloud, visite a página de modelos do Elementor e clique em "enviar para FULL."</p>
            <a href="<?= admin_url('edit.php?post_type=elementor_library&tabs_group=library') ?>" class="full-primary-button">
              Acessar
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>