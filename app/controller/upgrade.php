<?php

namespace Full\Customer\Upgrades;

use FULL_CUSTOMER;

defined('ABSPATH') || exit;

add_action('full-customer/upgrade/0.0.9', '\Full\Customer\Actions\verifySiteConnection');
add_action('full-customer/upgrade/0.1.1', function () {
  $full = new FULL_CUSTOMER();
  $full->set('allow_backlink', true);
});
