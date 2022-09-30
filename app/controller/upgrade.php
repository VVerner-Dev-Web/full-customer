<?php

namespace Full\Customer\Upgrades;

defined('ABSPATH') || exit;

add_action('full-customer/upgrade/0.0.9', '\Full\Customer\Actions\verifySiteConnection');
