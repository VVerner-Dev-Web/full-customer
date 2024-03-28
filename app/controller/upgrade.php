<?php

namespace Full\Customer\Upgrades;

defined('ABSPATH') || exit;

add_action('full-customer/upgrade/0.0.9', '\Full\Customer\Actions\verifySiteConnection');
add_action('full-customer/upgrade/0.1.1', function (): void {
  $full = fullCustomer();
  $full->set('allow_backlink', true);
});
