<?php

namespace Full\Customer\Actions;

defined('ABSPATH') || exit;

function initFullElementorTemplates(): void
{
  if (class_exists('\Elementor\Plugin')) :
    require_once FULL_CUSTOMER_APP . '/controller/elementor/hooks.php';
    require_once FULL_CUSTOMER_APP . '/controller/elementor/actions.php';
    require_once FULL_CUSTOMER_APP . '/controller/elementor/filters.php';
    require_once FULL_CUSTOMER_APP . '/controller/elementor/TemplateManager.php';
    require_once FULL_CUSTOMER_APP . '/controller/elementor/Importer.php';
    require_once FULL_CUSTOMER_APP . '/controller/elementor/Exporter.php';
  endif;
}
