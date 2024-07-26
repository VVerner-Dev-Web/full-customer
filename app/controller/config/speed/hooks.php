<?php

namespace Full\Customer\Speed;

defined('ABSPATH') || exit;

add_action('wp_ajax_full/widget/speed-settings', 'Full\Customer\Speed\Actions\updateSettings');
