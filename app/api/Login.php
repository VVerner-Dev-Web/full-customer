<?php

namespace Full\Customer\Api;

use \FullCustomerController;
use \WP_REST_Server;
use \WP_REST_Request;
use \WP_REST_Response;

defined('ABSPATH') || exit;

class Login extends FullCustomerController
{
  private const TOKEN_KEY         = '_full-remote-login';
  private const TOKEN_EXPIRATION  = HOUR_IN_SECONDS;

  public static function registerRoutes(): void
  {
    $api = new self();

    register_rest_route(self::NAMESPACE, '/auth-token', [
      [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => [$api, 'processAuthTokenRequest'],
        'permission_callback' => 'is_user_logged_in',
      ]
    ]);

    register_rest_route(self::NAMESPACE, '/login/(?P<hash>[A-Z0-9]+)', [
      [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => [$api, 'processLogin'],
        'permission_callback' => '__return_true',
      ]
    ]);
  }

  public function processAuthTokenRequest(WP_REST_Request $request): WP_REST_Response
  {
    $fullToken = $request->get_header('x-full');
    $fullTokenEnv = $request->get_header('x-env') ? $request->get_header('x-env') : 'prd';
    $fullTokenEnv = strtoupper($fullTokenEnv);

    if (!$fullToken || !$this->validateReceivedFullToken($fullToken, $fullTokenEnv)) :
      return new WP_REST_Response([], 401);
    endif;

    $this->deleteAuthToken();

    return new WP_REST_Response([
      'token' => $this->createAuthToken(),
    ]);
  }

  public function processLogin(WP_REST_Request $request): ?WP_REST_Response
  {
    $hash   = $request->get_param('hash');
    $hash   = explode(':', base64_decode($hash));

    $token  = array_shift($hash);
    $user   = array_shift($hash);

    if ($token !== $this->getAuthToken()) :
      return new WP_REST_Response([], 401);
    endif;

    $this->deleteAuthToken();

    $isEmail  = strpos('@', $user) !== false;
    $getBy    = $isEmail ? 'email' : 'login';
    $user     = get_user_by($getBy, $user);

    if (!$user) :
      return new WP_REST_Response(['error' => 'user ' . $user . ' not found'], 401);
    endif;

    wp_clear_auth_cookie();
    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID);
    wp_redirect(admin_url());

    return null;
  }

  private function deleteAuthToken(): void
  {
    delete_transient(self::TOKEN_KEY);
  }

  private function createAuthToken(): string
  {
    $token = strtoupper(bin2hex(random_bytes(12)));
    set_transient(self::TOKEN_KEY, $token, self::TOKEN_EXPIRATION);
    return $token;
  }

  private function getAuthToken(): ?string
  {
    $token = get_transient(self::TOKEN_KEY);
    return $token ? $token : null;
  }

  private function validateReceivedFullToken(string $fullToken, string $env = null): bool
  {
    $site   = home_url();
    $site   = parse_url($site);

    $request = wp_remote_post($this->getFullAuthenticationEndpoint($env), [
      'sslverify' => false,
      'headers'   => [
        'Content-Type' => 'application/json'
      ],
      'body'      => json_encode([
        'token'     => $fullToken,
        'domain'    => isset($site['host']) ? $site['host'] : ''
      ])
    ]);

    return wp_remote_retrieve_response_code($request) === 200;
  }

  private function getFullAuthenticationEndpoint(string $env = null): string
  {
    $uri   = $this->env->getFullDashboardApiUrl($env);
    $uri  .= '/v1/validate-token/';

    return $uri;
  }
}
