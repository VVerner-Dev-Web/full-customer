<?php

namespace Full\Customer\AiImages;

defined('ABSPATH') || exit;

add_action('admin_menu', 'Full\Customer\AiImages\Actions\addMenuPages');
add_action('admin_enqueue_scripts', 'Full\Customer\AiImages\Actions\adminEnqueueScripts');

add_action('wp_ajax_full/ai/list-images-missing-alt', 'Full\Customer\AiImages\Actions\listImagesMissingAlt');
add_action('wp_ajax_full/ai/update-image-alt', 'Full\Customer\AiImages\Actions\imageAltUpdate');
add_action('wp_ajax_full/ai/generate-image-alt', 'Full\Customer\AiImages\Actions\imageAltGenerator');
