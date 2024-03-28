<?php

namespace Full\Customer\Images;

use Full\Customer\FileSystem;
use stdClass;

defined('ABSPATH') || exit;

class ImageOptimization
{
  public Settings $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if ($env->get('useImagify')) :
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

    $file = $upload['file'];
    $width = 0;
    $height = 0;

    if ($this->env->get('enableUploadResize')) :
      $size = getimagesize($file);

      $width = $size[0];
      $height = $size[1];

      $ratio = $width / $height;

      if ($ratio > 1) {
        $width = $this->env->get('resizeMaxSize');
        $height = $this->env->get('resizeMaxSize') / $ratio;
      } else {
        $width = $this->env->get('resizeMaxSize') * $ratio;
        $height = $this->env->get('resizeMaxSize');
      }
    endif;

    $url  = fullCustomer()->getFullDashboardApiUrl() . '-customer/v1/image-optimization';

    $payload = [
      'site'      => home_url(),
      'image'     => base64_encode(file_get_contents($file)),
      'filename'  => basename($file),
      'width'     => $width,
      'height'    => $height,
    ];

    $request  = wp_remote_post($url, ['sslverify' => false, 'body' => $payload]);
    $response = wp_remote_retrieve_body($request);
    $response = json_decode($response);

    if (isset($response->job) && $response->job) :
      $tempFile = download_url($response->job);
      (new FileSystem)->moveFile($tempFile, $file);
    endif;

    return $upload;
  }

  public static function getUsage(): ?stdClass
  {
    $url  = fullCustomer()->getFullDashboardApiUrl() . '-customer/v1/image-optimization-usage';

    $payload = [
      'site' => home_url()
    ];

    $request  = wp_remote_get($url, ['sslverify' => false, 'body' => $payload]);
    $response = wp_remote_retrieve_body($request);
    $response = json_decode($response);

    return $response ? $response : null;
  }
}

ImageOptimization::attach();
