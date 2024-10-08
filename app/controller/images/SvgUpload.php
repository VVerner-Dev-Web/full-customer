<?php

namespace Full\Customer\Images;

use enshrined\svgSanitize\Sanitizer;

defined('ABSPATH') || exit;

class SvgUpload
{
  public Settings $env;

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

  public function resize(array $mimes): array
  {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
  }

  public function confirmFileType(array $ext, $file, string $filename, $mimes): array
  {
    if (substr($filename, -4) === '.svg') :
      $ext['type'] = 'image/svg+xml';
      $ext['ext'] = 'svg';
    endif;
    return $ext;
  }

  public function sanitizeUpload(array $file): array
  {
    if (!isset($file['tmp_name'])) :
      return $file;
    endif;

    $tmpName = $file['tmp_name'];
    $filename = (isset($file['name']) ? $file['name'] : '');
    $fileTypeExt = wp_check_filetype_and_ext($tmpName, $filename);
    $fileType = (empty($fileTypeExt['type']) ? '' : $fileTypeExt['type']);

    if ('image/svg+xml' === $fileType) :
      $sanitizer = new Sanitizer();
      $sanitized = $sanitizer->sanitize(file_get_contents($tmpName));

      if (false === $sanitized) :
        $file['error'] = 'This SVG file could not be sanitized, so, was not uploaded for security reasons.';
      endif;

      fullFileSystem()->put_contents($tmpName, $sanitized);
    endif;

    return $file;
  }

  public function generateMetadata(array $metadata, int $id): array
  {
    if ('image/svg+xml' !== get_post_mime_type($id)) :
      return $metadata;
    endif;

    $path   = get_attached_file($id);
    $svg    = simplexml_load_file($path);
    $width  = 0;
    $height = 0;

    if ($svg) :
      $attributes = $svg->attributes();

      if (property_exists($attributes, 'width') && $attributes->width !== null && (property_exists($attributes, 'height') && $attributes->height !== null)) {
        $width = (int) (float) $attributes->width;
        $height = (int) (float) $attributes->height;
      } elseif (property_exists($attributes, 'viewBox') && $attributes->viewBox !== null) {
        $sizes = explode(' ', $attributes->viewBox);

        if (isset($sizes[2], $sizes[3])) {
          $width = (int) (float) $sizes[2];
          $height = (int) (float) $sizes[3];
        }
      }
    endif;

    $url = wp_get_original_image_url($id);
    $urlPath = str_replace(wp_upload_dir()['baseurl'] . '/', '', $url);

    $metadata['width'] = $width;
    $metadata['height'] = $height;
    $metadata['file'] = $urlPath;

    return $metadata;
  }

  public function getAttachmentUrl(): void
  {
    $id = (isset($_REQUEST['attachmentID']) ? $_REQUEST['attachmentID'] : '');

    if ($id) {
      esc_url(wp_get_attachment_url($id));
      die;
    }
  }

  public function getUrlInLibrary(array $response): array
  {
    if ($response['mime'] === 'image/svg+xml') :
      $response['image'] = ['src' => $response['url']];
    endif;

    return $response;
  }
}

SvgUpload::attach();
