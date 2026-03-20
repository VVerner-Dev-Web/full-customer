<?php

defined('ABSPATH') || exit;

class FullCustomerActivation
{
  public function __construct()
  {
    add_filter('full-customer/active-widgets-menu', [$this, 'menuPage']);
    add_action('admin_enqueue_scripts', [$this, 'assets'], PHP_INT_MAX);

    add_action('wp_ajax_full/activation/repository', [$this, 'repository']);
    add_action('wp_ajax_full/activation/install-plugin', [$this, 'installPlugin']);
    add_action('wp_ajax_full/activation/activate-plugin', [$this, 'activatePlugin']);
    add_action('wp_ajax_full/activation/install-plugin/progress', [$this, 'installationProgress']);
  }

  public function menuPage(array $pages): array
  {
    $pages[] = [
      'name' => 'Ativar Plugins',
      'endpoint' => 'full-activation'
    ];
    return $pages;
  }

  public function assets(): void
  {
    if (!current_user_can('manage_options')) {
      return;
    }

    $version = getFullAssetsVersion();
    $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';
    $id = wp_generate_uuid4();

    wp_enqueue_style('full-activation', $baseUrl . 'css/activation.css', [], $version);
    wp_enqueue_script('full-activation', $baseUrl . 'js/activation.js', ['jquery'], $version, true);
    wp_localize_script('full-activation', 'FULL_ACTIVATION', [
      'wpPluginsUrl' => admin_url('plugins.php'),
      'repository' => add_query_arg([
        'action'  => 'full/activation/repository',
        'nonce'   => wp_create_nonce('full/activation/repository')
      ], admin_url('admin-ajax.php')),
      'installPlugin' => add_query_arg([
        'id'      => $id,
        'action'  => 'full/activation/install-plugin',
        'nonce'   => wp_create_nonce('full/activation/install-plugin')
      ], admin_url('admin-ajax.php')),
      'installPluginProgress' => add_query_arg([
        'id'      => $id,
        'action'  => 'full/activation/install-plugin/progress',
        'nonce'   => wp_create_nonce('full/activation/install-plugin/progress')
      ], admin_url('admin-ajax.php'))
    ]);
  }

  public function repository(): void
  {
    if (!current_user_can('manage_options') || !wp_verify_nonce(filter_input(INPUT_GET, 'nonce'), 'full/activation/repository')) {
      wp_send_json_error();
    }

    $dir = array_map([$this, 'localPluginInfo'], FullCustomerUpdate::fetchDirectory(false));
    $dir = array_filter($dir, fn($plugin) => !str_contains($plugin->plugin, 'full-customer') && !$plugin->is_addon);

    wp_send_json_success(array_values($dir));
  }

  private function localPluginInfo(stdClass $plugin): stdClass
  {
    $plugin->exists = file_exists(trailingslashit(WP_PLUGIN_DIR) . $plugin->plugin);
    $plugin->is_active = is_plugin_active($plugin->plugin);

    return $plugin;
  }

  private function getCurrentCookies(): array
  {
    $cookies = [];
    foreach ($_COOKIE as $name => $value) {
      $cookies[] = new WP_Http_Cookie(['name' => $name, 'value' => $value]);
    }
    return $cookies;
  }

  public function installPlugin(): void
  {
    if (!current_user_can('manage_options') || !wp_verify_nonce(filter_input(INPUT_GET, 'nonce'), 'full/activation/install-plugin')) {
      wp_send_json_error('Sem permissão');
    }

    require_once ABSPATH . 'wp-admin/includes/plugin.php';

    global $wp_filesystem;

    if (!is_a($wp_filesystem, 'WP_Filesystem_Base')) {
      include_once(ABSPATH . 'wp-admin/includes/file.php');
      $creds = request_filesystem_credentials(site_url());
      wp_filesystem($creds);
    }

    $id  = filter_input(INPUT_GET, 'id') ?? uniqid();
    $dir = FullCustomerUpdate::fetchDirectory(false);
    $key = filter_input(INPUT_POST, 'plugin') ?? '';
    $plugin = isset($dir[$key]) ? self::localPluginInfo($dir[$key]) : null;

    if (!$plugin) {
      wp_send_json_error('Plugin não localizado');
    }

    $this->enqueueInstallationProgress($id, $key, 'Verificando status atual do plugin no site');

    if (!$plugin->exists) {
      $this->enqueueInstallationProgress($id, $key, 'Plugin não instalado, iremos fazer a instalação');
      $this->enqueueInstallationProgress($id, $key, 'Verificando dependências');

      foreach ($plugin->dependencies as $dep) {
        $request = new WP_REST_Request('POST', '/wp/v2/plugins');
        $request->set_param('slug', $dep);
        $request->set_param('status', 'active');
        $request->set_param('context', 'edit');

        $this->enqueueInstallationProgress($id, $key, 'Instalando dependência ' . $dep);
        rest_do_request($request);
      }

      $this->enqueueInstallationProgress($id, $key, 'Dependências validadas. Iniciando processo do plugin principal');

      $recoveryLink = ' <a href="' . $plugin->package . '">Baixar plugin</a> ';
      $package = download_url($plugin->package, 300);

      if (is_wp_error($package)) {
        wp_send_json_error('[download] ' . $package->get_error_message() .  $recoveryLink);
      }

      $this->enqueueInstallationProgress($id, $key, 'Arquivo baixado. Iniciando descompactação...');

      $workingDir = $wp_filesystem->wp_content_dir() . 'upgrade/' . $plugin->slug;

      if ($wp_filesystem->is_dir($workingDir)) {
        $wp_filesystem->delete($workingDir, true);
      }

      wp_mkdir_p($workingDir);

      $done = unzip_file($package, $workingDir);

      if (is_wp_error($done)) {
        wp_send_json_error('[unzip] ' . $done->get_error_message() .  $recoveryLink);
      }

      $this->enqueueInstallationProgress($id, $key, 'Arquivo descompactado. Iniciando a transferência...');

      $wp_filesystem->delete($package);

      $done = copy_dir($workingDir, WP_PLUGIN_DIR);
      if (is_wp_error($done)) {
        wp_send_json_error('[copy] ' . $done->get_error_message() .  $recoveryLink);
      }

      $wp_filesystem->delete($workingDir, true);

      $pluginActivationPath = trailingslashit(WP_PLUGIN_DIR) . $plugin->plugin;

      $this->enqueueInstallationProgress($id, $key, 'Arquivo transferido. Solicitando ativação do plugin no WordPress');
    }

    if (!$plugin->is_active) {
      $url = add_query_arg([
        'action' => 'full/activation/activate-plugin',
        'nonce'  => wp_create_nonce('full/activation/activate-plugin'),
        'plugin' => $plugin->plugin
      ], admin_url('admin-ajax.php'));

      $response = wp_remote_get($url, [
        'sslverify' => false,
        'cookies'   => $this->getCurrentCookies(),
        'timeout'   => 30
      ]);

      $done = wp_remote_retrieve_body($response);
      $done = json_decode($done, true);

      if (!$done['success']) {
        wp_send_json_error('[activate] Houve um erro ao ativar o plugin.');
      }

      $this->enqueueInstallationProgress($id, $key, 'Plugin ativado com sucesso!');
    }

    $this->enqueueInstallationProgress($id, $key, 'Iniciando processo de ativação de licença');

    $activationUrl = getFullDashboardApiUrl('/v1/plugin/activate/' . $plugin->slug);

    $this->enqueueInstallationProgress($id, $key, 'Este processo pode demorar até 5 minutos, não feche a página antes disso.');

    $done = wp_remote_post($activationUrl, [
      'headers'   => [
        'x-cookies' => base64_encode(wp_json_encode($this->getCurrentCookies())),
      ],
      'sslverify' => false,
      'timeout'   => 300
    ]);

    $this->enqueueInstallationProgress($id, $key, 'Avaliando retorno dos técnicos...');

    $done = json_decode(wp_remote_retrieve_body($done), true);

    if (json_last_error() !== JSON_ERROR_NONE || !is_array($done) || empty($done)) {
      wp_send_json_error('O processo falhou por um motivo desconhecido. Recarrega a página para confirmar a ativação');
    }

    wp_send_json($done);
  }

  public function activatePlugin(): void
  {
    if (!current_user_can('manage_options') || !wp_verify_nonce(filter_input(INPUT_GET, 'nonce'), 'full/activation/activate-plugin')) {
      wp_send_json_error('Sem permissão');
    }

    $plugin = filter_input(INPUT_GET, 'plugin') ?? '';

    if (!$plugin) {
      wp_send_json_error('Plugin não localizado');
    }

    if (!is_plugin_active($plugin)) {
      activate_plugin($plugin);
    }

    wp_send_json_success();
  }

  public function installationProgress(): void
  {
    $id  = filter_input(INPUT_GET, 'id') ?? uniqid();
    $key = filter_input(INPUT_POST, 'plugin') ?? '';

    $progress = $this->getInstallationProgress($id)[$key] ?? [];

    wp_send_json_success('> ' . implode('<br>> ', $progress));
  }

  private function enqueueInstallationProgress(string $processId, string $plugin, string $message): void
  {
    $progress = $this->getInstallationProgress($processId);

    if (!isset($progress[$plugin])) {
      $progress[$plugin] = [];
    }

    $progress[$plugin][] = $message;

    set_transient($processId, $progress, HOUR_IN_SECONDS);
  }

  private function getInstallationProgress(string $processId): array
  {
    return get_transient($processId) ?: [];
  }
}

new FullCustomerActivation();
