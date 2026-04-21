<?php

namespace FC\Services;

class Analytics
{
  public function __construct()
  {
    register_activation_hook(FULL_CUSTOMER_FILE, [$this, 'activationAnalyticsHook']);
    register_deactivation_hook(FULL_CUSTOMER_FILE, [$this, 'deactivationAnalyticsHook']);
  }

  public function activationAnalyticsHook(): void
  {
    fcDashboardAPI()->fetch('POST', 'analytics/fc-status/active');
  }

  public function deactivationAnalyticsHook(): void
  {
    fcDashboardAPI()->fetch('POST', 'analytics/fc-status/inactive');
  }
}
