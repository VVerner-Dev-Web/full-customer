<?php

namespace Full\Customer\Filters;

defined('ABSPATH') || exit;

function versionsWithUpgrade(array $versions): array
{
  $versions[] = '0.0.9';

  return $versions;
}
