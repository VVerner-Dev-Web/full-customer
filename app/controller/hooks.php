<?php defined('ABSPATH') || exit;

add_action('rest_api_init', ['FULL_CUSTOMER_Login', 'registerRoutes']);
