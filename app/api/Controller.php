<?php defined('ABSPATH') || exit;

/**
 * @SuppressWarnings(PHPMD.MissingImport)
 */
abstract class FullCustomerController extends WP_REST_Controller
{
  protected const NAMESPACE = 'full-customer';
  protected $env;

  public function __construct()
  {
    $this->env = new FullCustomer();
  }
}
