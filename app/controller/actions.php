<?php 

namespace Full\Customer\Actions;

defined('ABSPATH') || exit;

function insertFooterNote(): void
{
    require_once FULL_CUSTOMER_APP . '/views/footer/note.php';
}
