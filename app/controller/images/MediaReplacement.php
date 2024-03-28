<?php

namespace Full\Customer\Images;

use WP_Post;

defined('ABSPATH') || exit;

class MediaReplacement
{
  public Settings $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if ($env->get('enableMediaReplacement')) :
      $cls = new self($env);
      add_filter('media_row_actions', [$cls, 'modifyEditLink'], PHP_INT_MAX, 2);
      add_filter('attachment_fields_to_edit', [$cls, 'enqueueFields'], PHP_INT_MAX, 2);
      add_action('edit_attachment', [$cls, 'replaceMedia']);
      add_filter('post_updated_messages', [$cls, 'attachment_updated_custom_message']);

      add_filter('wp_calculate_image_srcset', [$cls, 'appendCacheClearParamToImageSrcset'], PHP_INT_MAX, 5);
      add_filter('wp_get_attachment_image_src', [$cls, 'appendCacheClearParamToImageSrc'], PHP_INT_MAX, 2);
      add_filter('wp_prepare_attachment_for_js', [$cls, 'appendCacheClearParamToImageJs'], PHP_INT_MAX, 2);
    endif;
  }

  public function modifyEditLink(array $actions, WP_Post $post): array
  {
    $actions['edit'] = '<a href="' . get_edit_post_link($post) . '" aria-label="Editar ou Sobrescrever">Editar ou Sobrescrever</a>';
    return $actions;
  }

  public function enqueueFields(array $fields, $post): array
  {
    wp_enqueue_media();

    $fields['full-media-replace'] = [
      'label' => '',
      'input' => 'html',
      'html' => '
			<div id="media-replace-div" class="postbox">
				<div class="postbox-header">
					<h2 class="hndle ui-sortable-handle">FULL.images</h2>
				</div>
				<div class="inside">
				<input type="hidden" id="full-replace-id" name="full-replace-id" />
				<input type="hidden" id="full-current-id" value="' . $post->ID . '" />
				<div>
          <p>O arquivo atual será substituído pelo arquivo carregado e/ou selecionado, mantendo o ID atual, a data de publicação e o nome do arquivo. Assim, nenhum link existente será quebrado.</p>
          <p>Note que você só pode substituir pelo mesmo tipo de arquivo, por exemplo. JPG só pode ser substituído por JPG.</p>
        </div>

        <button type="button" id="full-media-replace" class="button-secondary button-large full-media-replace-button">Enviar novo arquivo</button>
				</div>
			</div>'
    ];

    return $fields;
  }

  public static function replaceMedia(int $oldAttachmentId, int $replaceId = null)
  {
    $replaceId = $replaceId ?? filter_input(INPUT_POST, 'full-replace-id', FILTER_VALIDATE_INT);

    if (!$replaceId) :
      return;
    endif;

    $oldPost = get_post($oldAttachmentId, ARRAY_A);
    $oldPostMime = $oldPost['post_mime_type'];

    $newPost = get_post($replaceId, ARRAY_A);
    $newPostMime = $newPost['post_mime_type'];

    if ($oldPostMime !== $newPostMime) :
      return;
    endif;

    $new_attachment_meta = wp_get_attachment_metadata($replaceId);

    if (array_key_exists('original_image', $new_attachment_meta)) {
      $new_media_file_path = wp_get_original_image_path($replaceId);
    } else {
      $new_attachment_file = get_post_meta($replaceId, '_wp_attached_file', true);
      $upload_dir = wp_upload_dir();
      $new_media_file_path = $upload_dir['basedir'] . '/' . $new_attachment_file;
    }

    if (!is_file($new_media_file_path)) {
      return false;
    }

    self::deleteFiles($oldAttachmentId);

    if (array_key_exists('original_image', $new_attachment_meta)) {
      $old_media_file_path = wp_get_original_image_path($oldAttachmentId);
    } else {
      $old_attachment_file = get_post_meta($oldAttachmentId, '_wp_attached_file', true);
      $old_media_file_path = $upload_dir['basedir'] . '/' . $old_attachment_file;
    }

    if (!file_exists(dirname($old_media_file_path))) {
      mkdir(dirname($old_media_file_path), 0755, true);
    }

    copy($new_media_file_path, $old_media_file_path);

    $old_media_post_meta_updated = wp_generate_attachment_metadata($oldAttachmentId, $old_media_file_path);
    wp_update_attachment_metadata($oldAttachmentId, $old_media_post_meta_updated);
    wp_delete_attachment($replaceId, true);

    set_transient('full/image-replaced/' . $oldAttachmentId, 1, DAY_IN_SECONDS);
  }

  public static function deleteFiles(int $postId): void
  {
    $attachment_meta = wp_get_attachment_metadata($postId);
    $attachment_file_path = get_attached_file($postId);
    $attachment_file_basename = basename($attachment_file_path);
    if (isset($attachment_meta['sizes']) && is_array($attachment_meta['sizes'])) {
      foreach ($attachment_meta['sizes'] as $size_info) {
        $intermediate_file_path = str_replace($attachment_file_basename, $size_info['file'], $attachment_file_path);
        wp_delete_file($intermediate_file_path);
      }
    }
    wp_delete_file($attachment_file_path);
    if (array_key_exists('original_image', $attachment_meta)) {
      $attachment_original_file_path = wp_get_original_image_path($postId);
      wp_delete_file($attachment_original_file_path);
    }
  }

  public function attachment_updated_custom_message(array $messages): array
  {
    if (isset($messages['attachment'])) :
      $messages['attachment'][4] = 'Imagem sobrescrita! Certifique-se de limpar o cache do seu navegador para visualizar as alterações';
    endif;

    return $messages;
  }

  public function appendCacheClearParamToImageSrcset(array $sources, $size_array, $image_src, $image_meta, $id): array
  {
    if (!get_transient('full/image-replaced/' . $id) || !is_admin()) :
      return $sources;
    endif;

    foreach ($sources as $size => $source) {
      $source['url'] .= ((false === strpos($source['url'], '?') ? '?' : '&')) . 't=' . time();
      $sources[$size] = $source;
    }

    return $sources;
  }

  public function appendCacheClearParamToImageSrc($image, $id): array
  {
    if (!$image || !get_transient('full/image-replaced/' . $id) || !is_admin() || empty($image[0])) :
      return [];
    endif;

    $image[0] .= ((false === strpos($image[0], '?') ? '?' : '&')) . 't=' . time();
    return $image;
  }

  public function appendCacheClearParamToImageJs($response, $attachment): array
  {
    if (!get_transient('full/image-replaced/' . $attachment->ID) || !is_admin()) :
      return $response;
    endif;

    if (false !== strpos($response['url'], '?')) :
      $response['url'] .= ((false === strpos($response['url'], '?') ? '?' : '&')) . 't=' . time();
    endif;

    if (isset($response['sizes'])) :
      foreach ($response['sizes'] as $size_name => $size) :
        $response['sizes'][$size_name]['url'] .= ((false === strpos($size['url'], '?') ? '?' : '&')) . 't=' . time();
      endforeach;
    endif;

    return $response;
  }
}

MediaReplacement::attach();
