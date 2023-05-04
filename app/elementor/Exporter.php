<?php

namespace Full\Customer\Elementor;

use WP_Error;
use function wp_parse_args;
use function current_user_can;
use Elementor\Core\Settings\Page\Model;

use Elementor\Plugin as ElementorPlugin;
use Elementor\TemplateLibrary\Source_Local as ElementorLocal;

class Exporter extends ElementorLocal
{
  public function export(int $postId): string
  {
    $data = $this->get_data(['template_id' => $postId]);

    if (!array_key_exists('content', $data)) {
      $data = [
        'content' => $data,
      ];
    }

    return wp_slash(json_encode(
      $data,
      JSON_UNESCAPED_LINE_TERMINATORS | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE
    ));
  }
}
