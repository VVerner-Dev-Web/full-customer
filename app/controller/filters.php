<?php

namespace Full\Customer\Filters;

defined('ABSPATH') || exit;

function versionsWithUpgrade(array $versions): array
{
  $versions[] = '0.0.9';
  $versions[] = '0.1.1';

  return $versions;
}
