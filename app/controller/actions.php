<?php 

namespace Full\Customer\Actions;

defined('ABSPATH') || exit;

function insertFooterNote(): void
{
    require_once FULL_CUSTOMER_APP . '/views/footer/note.php';
}

function activationHook(): void
{
    $url = defined('FULL_CUSTOMER_ENV') && FULL_CUSTOMER_ENV === 'DEV' ?
            'https://full.dev' :
            'https://painel.fullstackagency.club';

    wp_remote_post($url . '/wp-json/full-customer/v1/analytics', [
        'sslverify' => false,
        'headers'   => ['x-full' => 'Jkd0JeCPm8Nx', 'Content-Type' => 'application/json'],
        'body'      => json_encode([
            'site_url'      => home_url(),
            'admin_email'   => get_bloginfo('admin_email'),
            'plugin_status' => 'active'
        ])
    ]);
}

function deactivationHook(): void
{
    $url = defined('FULL_CUSTOMER_ENV') && FULL_CUSTOMER_ENV === 'DEV' ?
            'https://full.dev' :
            'https://painel.fullstackagency.club';

    wp_remote_post($url . '/wp-json/full-customer/v1/analytics', [
        'sslverify' => false,
        'headers'   => ['x-full' => 'Jkd0JeCPm8Nx', 'Content-Type' => 'application/json'],
        'body'      => json_encode([
            'site_url'      => home_url(),
            'admin_email'   => get_bloginfo('admin_email'),
            'plugin_status' => 'inactive'
        ])
    ]);
}
