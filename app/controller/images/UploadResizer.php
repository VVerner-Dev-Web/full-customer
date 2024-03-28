<?php

namespace Full\Customer\Images;

defined('ABSPATH') || exit;

class UploadResizer
{
  public Settings $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if ($env->get('enableUploadResize') && !$env->get('useImagify')) :
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

    $maxSize = $this->env->get('resizeMaxSize');

    if (isset($imageSize['width']) && $imageSize['width'] > $maxSize) :
      $editor->resize($maxSize, null, false);
    endif;

    $imageSize = $editor->get_size();

    if (isset($imageSize['height']) && $imageSize['height'] > $maxSize) :
      $editor->resize(null, $maxSize, false);
    endif;

    $editor->save($upload['file']);

    return $upload;
  }
}

UploadResizer::attach();
