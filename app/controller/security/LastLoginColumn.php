<?php

namespace Full\Customer\Security;

defined('ABSPATH') || exit;

class LastLoginColumn
{
  public Settings $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if (!$env->get('enableLastLoginColumn')) :
      return;
    endif;

    $cls = new self($env);
    add_action('wp_login', [$cls, 'storeLoginDate']);
    add_filter('manage_users_columns', [$cls, 'addColumn']);
    add_filter('manage_users_custom_column', [$cls, 'displayLastLoginDate'], 10, 3);
  }
  public function storeLoginDate(string $login): void
  {
    $user = get_user_by('login', $login);
    update_user_meta($user->ID, 'full-customer/last-login-date', time());
  }

  public function addColumn(array $columns): array
  {
    $columns['full_customer_last_login'] = 'Último login';
    return $columns;
  }

  public function displayLastLoginDate($value, string $col, int $userId)
  {
    if ('full_customer_last_login' === $col) :
      $timestamp = (int) get_user_meta($userId, 'full-customer/last-login-date', true);
      $value = 'Ainda não fez login';

      if ($timestamp !== 0) :
        $value = wp_date('d/m/Y \à\s H:i', $timestamp);
      endif;
    endif;

    return $value;
  }
}

LastLoginColumn::attach();
