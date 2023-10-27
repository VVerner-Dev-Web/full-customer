<?php

namespace Full\Customer\Login\Filters;

defined('ABSPATH') || exit;

function manageElementorLibraryPostsColumns(array $columns): array
{
  $columns['full_templates'] = 'FULL. Templates';
  return $columns;
}
