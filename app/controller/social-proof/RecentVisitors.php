<?php

namespace Full\Customer\SocialProof;

class RecentVisitors
{
  private Settings $env;
  private array $enabledOn = [];

  private function __construct()
  {
    $this->env = new Settings();
    $this->enabledOn = is_array($this->env->get('visitorsEnabledOn')) ? $this->env->get('visitorsEnabledOn') : [];
  }

  public static function attach(): void
  {
    $cls = new self();

    if (!$cls->env->get('enableRecentVisitors')) :
      return;
    endif;

    add_action('shutdown', [$cls, 'updateVisitors']);
    add_action('wp_footer', [$cls, 'addPopup']);
    add_action('wp_enqueue_scripts', [$cls, 'enqueueScripts']);
    add_action('add_meta_boxes', [$cls, 'addMetaboxes']);
    add_action('save_post', [$cls, 'updateState']);
  }

  public function updateState(int $postId): void
  {
    if (!in_array(get_post_type($postId), $this->enabledOn) || wp_is_post_revision($postId)) :
      return;
    endif;

    update_post_meta($postId, 'fullVisitorsState', filter_input(INPUT_POST, 'fullVisitorsState') ? filter_input(INPUT_POST, 'fullVisitorsState') : 'on');
  }

  public function addMetaboxes(): void
  {
    add_meta_box(
      'full-visitors',
      'Visitas recentes',
      [$this, 'renderMetabox'],
      $this->enabledOn,
      'side',
      'high'
    );
  }

  public function renderMetabox(): void
  {
    $current = get_post_meta(get_the_ID(), 'fullVisitorsState', true);
    echo '
    <label for="fullVisitorsState">Exibir popup de visitas recentes</label>
    <select name="fullVisitorsState" id="fullVisitorsState" style="style="width: 90%;margin-top: 10px;">
      <option value="on" ' . ($current === 'on' ? ' selected' : '') . '>Ativo</option>
      <option value="off" ' . ($current === 'off' ? ' selected' : '') . '>Inativo</option>
    </select>
    ';
  }

  public function updateVisitors(): void
  {
    if (!is_singular($this->enabledOn)) :
      return;
    endif;

    $window = (int) $this->env->get('visitorTrackingWindow') * 60;
    $limit  = time() + $window;

    $visitors = get_post_meta(get_the_ID(), 'full/visitors', true);
    $visitors = is_array($visitors) ? $visitors : [];

    $visitors = array_filter($visitors, fn ($time) => $time < $limit);
    $visitors[] = $limit;

    update_post_meta(get_the_ID(), 'full/visitors', $visitors);
  }

  public function addPopup(): void
  {
    if (!is_singular($this->enabledOn) || get_post_meta(get_the_ID(), 'fullVisitorsState', true) === 'off') :
      return;
    endif;

    $visitors = get_post_meta(get_the_ID(), 'full/visitors', true);

    if (!$visitors) :
      return;
    endif;

    $position = $this->env->get('ordersPopupPosition');
    $visitors = count($visitors);
    $visitors .= ' ' . ($visitors > 1 ? 'pessoas visitaram' : 'pessoa visitou');
    $imageSrc = get_the_post_thumbnail_url(get_the_ID(), 'original');

    $window  = (int) $this->env->get('visitorTrackingWindow');
    $window .= ' ' . ($window > 1 ? 'minutos' : 'minuto');

    $img = $imageSrc ? '<img src="' . $imageSrc . '">' : '';

    echo '
    <div id="full-woo-visitors-popup" class="full-woo-orders-popup full-social-proof-social ' . $position . '">
      <span class="dismiss-woo-order-popup">&times;</span>
      <div class="full-woo-orders-popup-inner">
        <div class="customer-information">
          <p><strong>' . $visitors . '</strong> este link nos últimos ' . $window .  '</p>
        </div>
        ' . $img . '
      </div>
    </div>';
  }

  public function enqueueScripts(): void
  {
    $version = getFullAssetsVersion();
    $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

    wp_enqueue_style('full-social-proof', $baseUrl . 'css/social-proof.css', [], $version);
    wp_enqueue_script('full-social-proof', $baseUrl . 'js/social-proof.js', ['jquery'], $version, true);
  }
}

RecentVisitors::attach();
