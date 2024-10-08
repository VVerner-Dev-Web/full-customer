<?php $expirationDate = $user ? get_user_meta($user->ID, 'full/expirationDate', true) : ''; ?>

<div id="full-temporary-settings">
  <h3>FULL.acess</h3>
  <table class="form-table">
    <tr>
      <th>
        <label for="full-expirationDate">Expiração do acesso</label>
      </th>
      <td>
        <input type="date" name="fullExpirationDate" id="full-expirationDate" class="regular-text" value="<?php echo $expirationDate ?>"><br>
        <span class="description">Deixe em branco para não definir uma data de expiração</span>
      </td>
    </tr>
  </table>
</div>