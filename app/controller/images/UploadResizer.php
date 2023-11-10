<?php

namespace Full\Customer\Images;

defined('ABSPATH') || exit;

class UploadResizer
{
  public $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if ($env->get('enableUploadResize')) :
      $cls = new self($env);
      add_filter('wp_handle_upload', [$cls, 'resize']);
    endif;
  }

  public function resize($upload)
  {
    $types = [
      'image/jpeg',
      'image/jpg',
      'image/webp',
      'image/png'
    ];

    if (is_wp_error($upload) || !in_array($upload['type'], $types) || filesize($upload['file']) <= 0) :
      return $upload;
    endif;

    $editor = wp_get_image_editor($upload['file']);
    $imageSize = $editor->get_size();

    $maxWidth = $this->env->get('resizeMaxWidth');
    $maxHeight = $this->env->get('resizeMaxHeight');
    $quality = $this->env->get('resizeQuality');

    if (isset($imageSize['width']) && $imageSize['width'] > $maxWidth) :
      $editor->resize($maxWidth, null, false);
    endif;

    $imageSize = $editor->get_size();

    if (isset($imageSize['height']) && $imageSize['height'] > $maxHeight) :
      $editor->resize(null, $maxHeight, false);
    endif;

    $editor->set_quality($quality);
    $editor->save($upload['file']);

    return $upload;
  }
}

UploadResizer::attach();
