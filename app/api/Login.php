<?php defined('ABSPATH') || exit;

class FULL_CUSTOMER_Login extends WP_REST_Controller
{
  private const NAMESPACE         = 'full-customer';
  private const TOKEN_KEY         = '_full-remote-login';
  private const TOKEN_EXPIRATION  = HOUR_IN_SECONDS;

  private $env;

  public function __construct()
  {
    $this->env = new FULL_CUSTOMER_Env();
  }

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

    register_rest_route(self::NAMESPACE, '/login/(?P<token>[A-Z0-9]+)', [
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
    $token = $request->get_param('token');

    if ($token !== $this->getAuthToken()) :
      return new WP_REST_Response([], 401);
    endif;

    $users = get_users([
      'role'      => 'administrator',
      'fields'    => 'ID',
      'number'    => 1
    ]);

    $uid  = array_shift($users);

    wp_clear_auth_cookie();
    wp_set_current_user($uid);
    wp_set_auth_cookie($uid);
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
