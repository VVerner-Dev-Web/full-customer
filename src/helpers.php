<?php

use FC\DashboardAPI;

function fcElementDataFragments(string $fragment, string $target, array $args = []): string
{
  return sprintf(
    ' data-fragment="%s" data-target="%s" data-args=\'%s\' ',
    $fragment,
    $target,
    wp_json_encode($args)
  );
}

function fcDashboardAPI(): DashboardAPI
{
  return new DashboardAPI();
}
