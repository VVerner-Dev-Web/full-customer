<?php

namespace Full\Customer\Access;

use DateTime;
use Exception;
use WP_Error;

class Hooks
{
  const USER_ROLE = 'full_temporary_access';

  private function __construct()
  {
  }

  public static function attach(): void
  {
    $cls = new self();

    add_action('show_user_profile', [$cls, 'addCustomProfileInputs']);
    add_action('edit_user_profile', [$cls, 'addCustomProfileInputs']);
    add_action("user_new_form", [$cls, 'addCustomProfileInputs']);

    add_action('user_register', [$cls, 'updateUserExpirationDate']);
    add_action('profile_update', [$cls, 'updateUserExpirationDate']);
  }

  public function addCustomProfileInputs($user): void
  {
    $user = is_object($user) ? $user : null;
    include_once FULL_CUSTOMER_APP . '/views/user-registration-fields.php';
  }

  public function updateUserExpirationDate(int $userId): void
  {
    $expirationDate = filter_input(INPUT_POST, 'fullExpirationDate');

    if (get_current_user_id() === $userId ||  !current_user_can('edit_user', $userId) || !$expirationDate) :
      return;
    endif;

    try {
      $date = new DateTime($expirationDate);
      update_user_meta($userId, 'full/expirationDate', $date->format('Y-m-d'));
    } catch (Exception $e) {
    }
  }

  public function verifyExpirationDate($user)
  {
    if (!is_wp_error($user)) :
      $now = new DateTime(current_time('Y-m-d'));
      $expirationDate = get_user_meta($user->ID, 'full/expirationDate', true);
      $expirationDate = $expirationDate ? new DateTime($expirationDate) : null;

      if ($expirationDate && $now > $expirationDate) :
        $user = new WP_Error('expired', '<strong>Erro:</strong> Seu acesso ao site está expirado, solicite ao administrador do site que ative-o novamente. O acesso expirou em ' . $expirationDate->format('d/m/Y') . '.');
      endif;
    endif;

    return $user;
  }
}

Hooks::attach();
