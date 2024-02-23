<?php

namespace Full\Customer\Access;

class Interaction
{
  private function __construct()
  {
  }

  public static function attach(): void
  {
    $cls = new self();

    add_filter('manage_users_columns', [$cls, 'addColumns'], PHP_INT_MAX);
    add_filter('manage_users_custom_column', [$cls, 'populateColumns'], 10, 3);
    add_action('wp_ajax_full/generate-temporary-token', [$cls, 'generateTemporaryToken']);
  }

  public function addColumns(array $cols): array
  {
    $cols['accessExpiration'] = 'Acesso válido até';

    if (current_user_can('manage_options')) :
      $cols['temporaryAccess'] = 'Acesso temporário';
    endif;

    return $cols;
  }

  public function populateColumns($val, string $col, int $userId)
  {
    if ('accessExpiration' === $col) :
      $expirationDate = get_user_meta($userId, 'full/expirationDate', true);
      $val = $expirationDate ? date_i18n('d/m/Y', strtotime($expirationDate)) : 'Indeterminado';
    elseif ('temporaryAccess' === $col) :
      $val = '<span class="button" data-js="full-generate-temporary-token" data-user="' . $userId . '">Gerar link</span>';
    endif;

    return $val;
  }

  public function generateTemporaryToken(): void
  {
    if (!current_user_can('manage_options')) :
      wp_send_json_error();
    endif;

    $userId = filter_input(INPUT_POST, 'userId', FILTER_VALIDATE_INT);
    $url = add_query_arg([
      'fta' => Authentication::generateAccessToken($userId)
    ], admin_url());

    wp_send_json_success($url);
  }
}

Interaction::attach();
