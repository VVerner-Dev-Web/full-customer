<?php

namespace Full\Customer\Elementor;

use Elementor\TemplateLibrary\Source_Local as ElementorLocal;

class Exporter extends ElementorLocal
{
  public function export(int $postId): string
  {
    $data = $this->get_data(['template_id' => $postId]);
    $data = is_array($data) ? $data : [];

    if (!isset($data['content'])) :
      $data['content'] = $data;
    endif;

    if (!isset($data['type'])) :
      $data['type'] = $this->get_template_type($postId);
    endif;

    return wp_slash(json_encode(
      $data,
      JSON_UNESCAPED_LINE_TERMINATORS | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE
    ));
  }
}
