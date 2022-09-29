<?php

namespace Full\Customer\Upgrades;

defined('ABSPATH') || exit;

add_action('full-customer/upgrade/0.0.9', function () {
  $flag = 'full-previous-connect-site-check';
  $full  = new FULL_CUSTOMER_Env();

  if (get_option($flag) || $full->isConnected()) :
    return;
  endif;

  $url = $full->getFullDashboardApiUrl() . '-customer/connect-site';

  $request  = wp_remote_get($url);
  $response = wp_remote_retrieve_body($request);
  $response = json_decode($response);

  if ($response->success) :
    $full->set('connection_email', sanitize_email($response->email));
    $full->set('dashboard_url', esc_url($response->dashboard_url));
  endif;

  update_option($flag, 1, false);
});
