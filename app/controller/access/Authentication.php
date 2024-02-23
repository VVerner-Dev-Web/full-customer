<?php

namespace Full\Customer\Access;

use DateTime;
use WP_Error;

class Authentication
{
  const USER_ROLE = 'full_temporary_access';

  private function __construct()
  {
  }

  public static function attach(): void
  {
    $cls = new self();

    add_filter('template_redirect', [$cls, 'maybeLogoutUser'], 3);
    add_filter('template_redirect', [$cls, 'maybeLoginUser'], 5);
    add_filter('wp_authenticate_user', [$cls, 'verifyExpirationDate'], PHP_INT_MAX);
  }

  public function maybeLoginUser(): void
  {
    $token = filter_input(INPUT_GET, 'fta') ? filter_input(INPUT_GET, 'fta') : '';

    if (!$token || is_user_logged_in()) :
      return;
    endif;

    $userId = $this->validateAccessToken($token);

    if (!$userId) :
      return;
    endif;

    wp_destroy_current_session();
    wp_clear_auth_cookie();
    wp_set_current_user(0);

    wp_set_current_user($userId);
    wp_set_auth_cookie($userId, true, false);

    wp_redirect(remove_query_arg('fta'));
    exit;
  }

  public static function generateAccessToken(int $userId): string
  {
    $token = bin2hex(random_bytes(10));
    set_transient('full/access-token/' . $userId, $token, HOUR_IN_SECONDS);
    return base64_encode($userId . ':' . $token);
  }

  private function validateAccessToken(string $token): int
  {
    $token = explode(':', base64_decode($token));
    if (count($token) !== 2) :
      return 0;
    endif;

    $userId = (int) $token[0];
    $token  = $token[1];

    $currentToken = get_transient('full/access-token/' . $userId);
    $isValidToken = $token === $currentToken;

    if (!$isValidToken) :
      return 0;
    endif;

    delete_transient('full/access-token/' . $userId);

    return $userId;
  }

  public function maybeLogoutUser(): void
  {
    if (!is_user_logged_in() || !$this->hasAccessExpired(get_current_user_id())) :
      return;
    endif;

    wp_logout();
    wp_redirect(wp_login_url());
    exit;
  }

  public function verifyExpirationDate($user)
  {
    if (!is_wp_error($user) && $this->hasAccessExpired($user->ID)) :
      $user = new WP_Error('expired', '<strong>Erro:</strong> Seu acesso ao site está expirado, solicite ao administrador do site que ative-o novamente.');
    endif;

    return $user;
  }

  private function hasAccessExpired(int $userId): bool
  {
    $now = new DateTime(current_time('Y-m-d'));
    $expirationDate = get_user_meta($userId, 'full/expirationDate', true);
    $expirationDate = $expirationDate ? new DateTime($expirationDate) : null;

    return $expirationDate && $now > $expirationDate;
  }
}

Authentication::attach();
