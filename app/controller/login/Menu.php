<?php

namespace Full\Customer\Login;

use Walker_Nav_Menu_Checklist;
use WP_Post;

defined('ABSPATH') || exit;

class Menu
{
  public Settings $env;
  public $loginSlug;

  private function __construct(Settings $env)
  {
    $this->env = $env;
    $this->loginSlug = $this->env->get('enableChangeLoginUrl') ? $this->env->get('changedLoginUrl') : '';
  }

  public static function attach(): void
  {
    $env = new Settings();

    if (!$env->get('loginNavMenuItem')) :
      return;
    endif;

    $cls = new self($env);

    add_action('admin_head-nav-menus.php', [$cls, 'addMetaBox']);
    add_filter('wp_setup_nav_menu_item', [$cls, 'dynamicUrls']);
    add_filter('wp_nav_menu_objects', [$cls, 'removeUnnecessaryMenuItems']);
  }

  public function addMetaBox(): void
  {
    add_meta_box(
      'add-login-logout',
      'Entrar/Sair',
      [$this, 'addMetaboxItems'],
      'nav-menus',
      'side',
      'default'
    );
  }

  /**
   * Add menu items for the login logout metabox
   *
   * @since 3.4.0
   */
  public function addMetaboxItems(): void
  {
    $menu_items = [
      'full-login'        => [
        'title'   => 'Entrar',
        'url'     => '#full-login',
        'classes' => ['full-menu-item', 'full-login']
      ],
      'full-logout'       => [
        'title'   => 'Sair',
        'url'     => '#full-logout',
        'classes' => ['full-menu-item', 'full-logout-menu-item']
      ],
      'full-toggle-login' => [
        'title'   => 'Entrar/Sair',
        'url'     => '#full-toggle-login',
        'classes' => ['full-menu-item', 'full-toggle-login'],
      ]
    ];

    $item_details = [
      'db_id'            => 0,
      'object'           => 'full_login',
      'object_id'        => '',
      'menu_item_parent' => 0,
      'type'             => 'custom',
      'title'            => '',
      'url'              => '',
      'target'           => '',
      'attr_title'       => '',
      'classes'          => [],
      'xfn'              => ''
    ];

    $menu_items_object = [];

    foreach ($menu_items as $item_id => $details) {
      $menu_items_object[$details['title']] = (object) $item_details;
      $menu_items_object[$details['title']]->object_id = $item_id;
      $menu_items_object[$details['title']]->title = $details['title'];
      $menu_items_object[$details['title']]->classes = $details['classes'];
      $menu_items_object[$details['title']]->url = $details['url'];
    }

    $this->menuItemView($menu_items_object);
  }

  private function menuItemView($menu_items_object): void
  {
    global  $nav_menu_selected_id;

    $walker = new Walker_Nav_Menu_Checklist([]);

?>
    <div id="login-logout-links" class="loginlinksdiv">
      <div id="tabs-panel-login-logout-links-all" class="tabs-panel tabs-panel-view-all tabs-panel-active">
        <ul id="login-logout-links-checklist" class="list:login-logout-links categorychecklist form-no-clear">
          <?= walk_nav_menu_tree(array_map('wp_setup_nav_menu_item', $menu_items_object), 0, (object) ['walker' => $walker]); ?>
        </ul>
      </div>
      <p class="button-controls">
        <span class="add-to-menu">
          <input type="submit" <?php disabled($nav_menu_selected_id, 0) ?> class="button-secondary submit-add-to-menu right" value="<?php echo  esc_attr('Add to Menu'); ?>" name="add-login-logout-links-menu-item" id="submit-login-logout-links" />
          <span class="spinner"></span>
        </span>
      </p>
    </div>
<?php
  }

  public function dynamicUrls(WP_Post $menuItem): WP_Post
  {
    global  $pagenow;

    if ($pagenow != 'nav-menus.php' && !defined('DOING_AJAX') && isset($menuItem->url) && !in_array('full-menu-item', $menuItem->classes)) {
      $loginUrl = $this->loginSlug ? get_site_url('', $this->loginSlug) : wp_login_url();

      switch ($menuItem->url) {
        case '#full-login':
          $menuItem->url = $loginUrl;
          break;
        case '#full-logout':
          $menuItem->url = wp_logout_url();
          break;
        case '#full-toggle-login':
          $menuItem->url = is_user_logged_in() ? wp_logout_url() : $loginUrl;
          $menuItem->title = is_user_logged_in() ? 'Sair' : 'Entrar';
          break;
      }
    }

    return $menuItem;
  }

  public function removeUnnecessaryMenuItems(array $menuItems): array
  {
    foreach ($menuItems as $index => $item) :
      if (in_array('full-login', $item->classes) && is_user_logged_in()) {
        unset($menuItems[$index]);
      }

      if (in_array('full-logout-menu-item', $item->classes) && !is_user_logged_in()) {
        unset($menuItems[$index]);
      }
    endforeach;

    return $menuItems;
  }
}

Menu::attach();
