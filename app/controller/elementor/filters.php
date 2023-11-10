<?php

namespace Full\Customer\Elementor\Filters;

defined('ABSPATH') || exit;

function manageElementorLibraryPostsColumns(array $columns): array
{
  $columns['full_templates'] = 'FULL.templates';
  return $columns;
}
