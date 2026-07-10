<?php

namespace FC;

use WP_User;
use Exception;

class User
{
  private bool $loggedIn;
  private int $id;
  private WP_User $user;

  private function __construct()
  {
    $this->user = wp_get_current_user();
    $this->id = $this->user->ID;
    $this->loggedIn = is_user_logged_in();
  }

  protected function __clone() {}
  public function __wakeup()
  {
    throw new Exception("Cannot unserialize a singleton.");
  }

  public static function instance(): User
  {
    static $instance = null;
    if ($instance === null) {
      $instance = new self();
    }
    return $instance;
  }

  public function wp(): WP_User
  {
    return $this->user;
  }

  public function getConnectionEmail(): ?string
  {
    if (defined('FULL_CUSTOMER_CONNECTION_EMAIL') && !empty(FULL_CUSTOMER_CONNECTION_EMAIL)) {
      return FULL_CUSTOMER_CONNECTION_EMAIL;
    }

    $email = $this->getMeta('connection-email');
    return !empty($email) ? (string) $email : null;
  }

  public function setConnectionEmail(string $email): void
  {
    $this->setMeta('connection-email', sanitize_email($email));

    // Invalida o cache da API do Dashboard (transients de repositório de plugins)
    $version = (int) get_option('fc_dapi_repo_version', 1);
    update_option('fc_dapi_repo_version', $version + 1, false);
  }

  public function isConnected(): bool
  {
    $email = $this->getConnectionEmail();
    return !empty($email) && is_email($email);
  }

  public function isAdmin(): bool
  {
    return $this->user->has_cap('manage_options');
  }

  private function getMeta(string $key)
  {
    if (!$this->loggedIn) {
      return null;
    }

    $value = get_user_meta($this->id, 'fc/' . $key, true);
    return $value !== '' ? $value : null;
  }

  private function setMeta(string $key, $value): void
  {
    if ($this->loggedIn) {
      update_user_meta($this->id, 'fc/' . $key, $value);
    }
  }
}
