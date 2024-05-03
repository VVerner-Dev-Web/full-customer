<?php

namespace Full\Customer\ElementorCrm;

class Hooks
{
  private Settings $env;

  private function __construct()
  {
    $this->env = new Settings();
  }

  public static function attach(): void
  {
    $cls = new self();

    add_action('admin_menu', [$cls, 'addMenuPage']);
    add_action('admin_enqueue_scripts', [$cls, 'adminEnqueueScripts']);
    add_action('elementor/frontend/before_render', [$cls, 'countFormView']);

    add_action('wp_ajax_full/widget/crm/form/set-stages', [$cls, 'processStageUpdate']);
    add_action('wp_ajax_full/widget/crm/form/get-analytics', [$cls, 'formAnalytics']);
    add_action('wp_ajax_full/widget/crm/form/get-leads', [$cls, 'getLeads']);

    add_action('wp_ajax_full/widget/crm/lead/update', [$cls, 'updateLead']);
    add_action('wp_ajax_full/widget/crm/lead/delete', [$cls, 'deleteLead']);
    add_action('wp_ajax_full/widget/crm/lead/hide', [$cls, 'hideLead']);
  }

  public function countFormView($element): void
  {
    if (!$element instanceof \ElementorPro\Modules\Forms\Widgets\Form) :
      return;
    endif;

    global $fullCrmFormsTracked;

    if (!isset($fullCrmFormsTracked)) :
      $fullCrmFormsTracked = [];
    endif;

    $key = 'full/crm/' . $element->get_id();

    if (current_user_can('manage_options') || in_array($key, $fullCrmFormsTracked)) :
      return;
    endif;

    $views = get_option($key, 0);
    update_option($key, ++$views);

    $fullCrmFormsTracked[] = $key;
  }

  public function addMenuPage(): void
  {
    add_submenu_page(
      'full-connection',
      'FULL.Elementor CRM',
      'FULL.Elementor CRM',
      'edit_posts',
      'full-crm',
      'fullGetAdminPageView'
    );
  }

  public function adminEnqueueScripts(): void
  {
    if (filter_input(INPUT_GET, 'page') !== 'full-crm') :
      return;
    endif;

    $version = getFullAssetsVersion();
    $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

    wp_enqueue_style('full-admin-crm', $baseUrl . 'css/crm.css', $version);
    wp_enqueue_script('full-admin-crm', $baseUrl . 'js/admin-crm.js', ['jquery', 'jquery-ui-sortable', 'jquery-ui-draggable'], $version, true);
    wp_localize_script('full-admin-crm', 'fullCrm', [
      'stages' => $this->env->get('stages'),
      'leadBaseUrl' => admin_url('admin.php?page=e-form-submissions#/')
    ]);
  }

  public function formAnalytics(): void
  {
    $formId = filter_input(INPUT_POST, 'formId');
    $stages = isset($this->env->get('stages')[$formId]) ? $this->env->get('stages')[$formId] : [];

    $response = [
      'values' => [
        'total_leads' => 0,
        'total_won' => 0,
        'total_lost' => 0,
        'capture_rate' => 0,
        'conversion_rate' => 0,
        'total_views' => 0
      ],
      'chart' => []
    ];

    if (!$formId || !$stages) :
      wp_send_json($response);
    endif;

    $response['chart'] = array_fill_keys(array_keys($stages), 0);

    $worker = new Leads();
    $funnel = $worker->leadsByStage($formId);
    $firstStage = array_key_first($stages);

    $response['values']['total_views'] = get_option('full/crm/' . $formId, 0);

    foreach ($funnel as $index => $value) :
      $key = array_key_exists($index, $stages) ? $index : $firstStage;
      $stage = $stages[$key];

      $response['values']['total_leads'] += $value;

      if ('won' === $stage['status']) :
        $response['values']['total_won'] += $value;
      elseif ('lost' === $stage['status']) :
        $response['values']['total_lost'] += $value;
      else :
        $response['chart'][$key] += $value;
      endif;
    endforeach;

    foreach ($response['chart'] as $index => $value) :
      $key = array_key_exists($index, $stages) ? $index : $firstStage;
      $stage = $stages[$key];

      if ($stage['status'] !== '') :
        unset($response['chart'][$index]);
      endif;
    endforeach;

    if ($response['values']['total_leads']) :
      $response['values']['conversion_rate'] = number_format_i18n($response['values']['total_won'] / $response['values']['total_leads'] * 100, 2) . '%';
    endif;

    if ($response['values']['total_views']) :
      $response['values']['capture_rate'] = number_format_i18n($response['values']['total_leads'] / $response['values']['total_views'] * 100, 2) . '%';
    endif;

    wp_send_json($response);
  }

  public function processStageUpdate(): void
  {
    check_ajax_referer('full/widget/crm/form/set-stages');

    $formId = filter_input(INPUT_POST, 'formId');
    $formStages = filter_input(INPUT_POST, 'stage', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
    $stages = $this->env->get('stages');

    $stages[$formId] = array_filter($formStages, fn ($row) => isset($row['name']) && $row['name']);

    $this->env->set('stages', $stages);

    wp_send_json_success([
      'stages' => $this->env->get('stages')
    ]);
  }

  public function getLeads(): void
  {
    $worker = new Leads();
    $formId = filter_input(INPUT_POST, 'formId');
    $stages = isset($this->env->get('stages')[$formId]) ? $this->env->get('stages')[$formId] : [];

    if (!$formId || !$stages) :
      wp_send_json([]);
    endif;

    $list = $worker->list($formId);

    $formatted  = [];
    $firstStage = array_key_first($stages);

    foreach ($list['data'] as $item) :
      if (!array_key_exists($item['status'], $stages)) :
        $item['status'] = $firstStage;
      endif;

      if (!isset($formatted[$item['status']])) :
        $formatted[$item['status']] = [];
      endif;

      $formatted[$item['status']][] = $item;
    endforeach;

    wp_send_json($formatted);
  }

  public function updateLead(): void
  {
    $submissionId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? 0;
    $stage = sanitize_title(filter_input(INPUT_POST, 'stage') ?? '');

    $worker = new Leads();

    $worker->updateStage($submissionId, $stage);

    wp_send_json_success();
  }

  public function deleteLead(): void
  {
    $submissionId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? 0;

    $worker = new Leads();

    $worker->delete($submissionId);

    wp_send_json_success();
  }

  public function hideLead(): void
  {
    $submissionId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? 0;

    $worker = new Leads();

    $worker->hide($submissionId);

    wp_send_json_success();
  }
}

Hooks::attach();
