<?php

namespace Full\Customer\ElementorCrm;

use ElementorPro\Modules\Forms\Submissions\Database\Query;

defined('ABSPATH') || exit;

class Leads
{
  private Query $query;

  public function __construct()
  {
    $this->query = Query::get_instance();
  }

  public function getForms(): array
  {
    global $wpdb;

    $sql = "SELECT DISTINCT(element_id), form_name FROM " . $this->query->get_table_submissions();
    $data = $wpdb->get_results($sql);

    $forms = [];

    if (is_array($data)) :
      foreach ($data as $row) :
        $forms[$row->element_id] = $row->form_name;
      endforeach;
    endif;

    return $forms;
  }

  public function list($formId): array
  {
    return $this->query->get_submissions([
      'per_page' => PHP_INT_MAX,
      'with_form_fields' => true,
      'with_meta' => true,
      'filters'  => [
        'form' => $formId,
        'ids'  => [
          'value' => $this->getFormVisibleIds($formId)
        ]
      ]
    ]);
  }

  public function leadsByStage($formId): array
  {
    global $wpdb;
    $sql = "SELECT status, COUNT(*) as total FROM `{$this->query->get_table_submissions()}` WHERE element_id = '$formId' GROUP BY status;";
    $results = $wpdb->get_results($sql);

    return array_combine(
      array_column($results, 'status'),
      array_map('intval', array_column($results, 'total'))
    );
  }

  private function getFormVisibleIds($formId): array
  {
    global $wpdb;
    $sql = "SELECT id FROM {$this->query->get_table_submissions()} WHERE status != 'hidden' AND element_id = '$formId'";
    return array_map('intval', $wpdb->get_col($sql));
  }

  public function updateStage(int $leadId, string $stage): void
  {
    global $wpdb;

    $wpdb->update(
      $this->query->get_table_submissions(),
      ['status' => $stage],
      ['id' => $leadId]
    );
  }

  public function hide(int $leadId): void
  {
    $this->updateStage($leadId, 'hidden');
  }

  public function delete(int $leadId): void
  {
    $this->query->delete_submission($leadId);
  }
}
