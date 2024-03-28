<?php

namespace FULL\Customer\Shortcodes;

class Collection
{
  private array $cache = [];

  private function __construct()
  {
  }

  public static function list(): array
  {
    return array_filter(get_class_methods(__CLASS__), fn ($method): bool => 'full_' === substr($method, 0, 5));
  }

  public static function attach(): void
  {
    $cls = new self();

    foreach (self::list() as $method) :
      add_shortcode($method, [$cls, $method]);
    endforeach;
  }

  public function full_data_atual(): string
  {
    return current_time(get_option('date_format', 'd/m/Y'));
  }

  public function full_hora_atual(): string
  {
    return current_time(get_option('time_format', 'H:i:s'));
  }

  public function full_data_e_hora_atual(): string
  {
    return current_time(get_option('date_format', 'd/m/Y') . ' ' . get_option('time_format', 'H:i:s'));
  }

  public function full_cidade_do_visitante(): string
  {
    if (!isset($this->cache['location'])) :
      $this->fetchCustomerLocation();
    endif;

    return isset($this->cache['location']['city']) ? $this->cache['location']['city'] : 'Não identificado';
  }

  public function full_estado_do_visitante(): string
  {
    if (!isset($this->cache['location'])) :
      $this->fetchCustomerLocation();
    endif;

    return isset($this->cache['location']['region']) ? $this->cache['location']['region'] : 'Não identificado';
  }

  public function full_pais_do_visitante(): string
  {
    if (!isset($this->cache['location'])) :
      $this->fetchCustomerLocation();
    endif;

    return isset($this->cache['location']['country']) ? $this->cache['location']['country'] : 'Não identificado';
  }

  public function full_url_pagina_inicial(): string
  {
    return home_url();
  }

  public function full_id_do_usuario_atual(): string
  {
    $user = is_user_logged_in() ? wp_get_current_user() : null;
    return $user ? $user->ID : '';
  }

  public function full_nome_do_usuario_atual(): string
  {
    $user = is_user_logged_in() ? wp_get_current_user() : null;
    return $user ? $user->display_name : '';
  }

  public function full_email_do_usuario_atual(): string
  {
    $user = is_user_logged_in() ? wp_get_current_user() : null;
    return $user ? $user->user_email : '';
  }

  public function full_link_para_fazer_login(): string
  {
    return wp_login_url();
  }

  public function full_link_para_fazer_logout(): string
  {
    return wp_logout_url(home_url());
  }

  public function full_nome_do_site(): string
  {
    return get_bloginfo('name') ? get_bloginfo('name') : '';
  }

  public function full_descricao_do_site(): string
  {
    return get_bloginfo('description') ? get_bloginfo('description') : '';
  }

  public function full_url_pagina_de_loja(): string
  {
    return function_exists('WC') ? wc_get_page_permalink('shop') : 'WooCommerce não instalado';
  }

  public function full_url_pagina_de_carrinho(): string
  {
    return function_exists('WC') ? wc_get_cart_url() : 'WooCommerce não instalado';
  }

  public function full_url_pagina_de_checkout(): string
  {
    return function_exists('WC') ? wc_get_checkout_url() : 'WooCommerce não instalado';
  }

  public function full_url_pagina_de_minha_conta(): string
  {
    return function_exists('WC') ? wc_get_page_permalink('myaccount') : 'WooCommerce não instalado';
  }

  public function full_numero_aleatorio(): int
  {
    return random_int(0, 100);
  }

  public function full_hash_aleatorio(): string
  {
    return strtoupper(bin2hex(random_bytes(5)));
  }

  private function fetchCustomerLocation(): void
  {
    $ip = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP);

    $request = wp_remote_get("http://ipinfo.io/{$ip}/json");
    $details = wp_remote_retrieve_body($request);
    $details = json_decode($details, true);

    $this->cache['location'] = is_array($details) ? $details : null;
  }
}

Collection::attach();
