<?php

namespace FC\Services;

class MagicLink
{
  public function __construct()
  {
    fcRegisterRestRoute('POST', 'magic-link', [$this, 'generateLink'], '__return_true');
    add_action('init', [$this, 'handleMagicLinkLogin']);
  }

  public function generateLink(\WP_REST_Request $request): \WP_REST_Response
  {
    $signature = $request->get_param('signature');

    if (!$signature) {
      return new \WP_REST_Response(['error' => 'Assinatura não fornecida'], 400);
    }

    if (!$this->isValidSignature($signature)) {
      return new \WP_REST_Response(['error' => 'Assinatura inválida, recusada pelo painel ou timeout'], 403);
    }

    $conn = fcGetAnonymousUserConnection();

    if (!$conn) {
      return new \WP_REST_Response(['error' => 'Nenhuma conta FULL encontrada'], 403);
    }

    $token = wp_generate_password(32, false);
    set_transient('fc_magic_token_' . $token, $conn['user_id'], 3 * MINUTE_IN_SECONDS);

    $loginUrl = add_query_arg('fc_magic_token', $token, wp_login_url());

    return new \WP_REST_Response(['url' => $loginUrl], 200);
  }

  private function isValidSignature(string $signature): bool
  {
    $response = fcDashboardAPI('POST', 'staff/validate-magic-link', ['signature' => $signature]);
    return $response['success'] && $response['status'] === 200;
  }

  public function handleMagicLinkLogin(): void
  {
    $token = sanitize_text_field(filter_input(INPUT_GET, 'fc_magic_token') ?? '');

    if (!$token) {
      return;
    }

    $transientKey = 'fc_magic_token_' . $token;
    $userId = (int) get_transient($transientKey);

    if (!$userId) {
      wp_die('Link de login inválido ou expirado.', 'Acesso Negado', ['response' => 403]);
    }

    delete_transient($transientKey);

    wp_clear_auth_cookie();
    wp_set_current_user($userId);
    wp_set_auth_cookie($userId, false);

    wp_safe_redirect(admin_url());
    exit;
  }
}
