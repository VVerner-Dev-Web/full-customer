<dialog id="full-staff-modal">
  <div class="fsm-header">
    <h2>FULL. Técnicos - Instala fácil</h2>
    <button>&times;</button>
  </div>
  <form class="fsm-body">
    <input type="hidden" name="action" value="full/staff/install">
    <?php wp_nonce_field('full/staff/install'); ?>

    <div class="fsm-repository"></div>

    <button class="full-primary-button">Instalar plugins</button>

    <div class="fsm-response"></div>
  </form>
</dialog>