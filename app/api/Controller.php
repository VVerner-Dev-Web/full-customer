<?php defined('ABSPATH') || exit;

abstract class FULL_CUSTOMER_Controller extends WP_REST_Controller
{
  protected const NAMESPACE = 'full-customer';
  protected $env;

  public function __construct()
  {
    $this->env = new FullCustomer();
  }
}
