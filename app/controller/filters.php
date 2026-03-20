<?php

namespace Full\Customer\Filters;

defined('ABSPATH') || exit;

function setPluginBranding($plugins): array
{
  $key    = plugin_basename(FULL_CUSTOMER_FILE);

  if (!is_array($plugins) || !isset($plugins[$key])) :
    return $plugins;
  endif;

  $full = fullCustomer();

  $plugins[$key]['Name']        = $full->getBranding('plugin-name', $plugins[$key]['Name']);
  $plugins[$key]['Title']       = $full->getBranding('plugin-name', $plugins[$key]['Title']);
  $plugins[$key]['PluginURI']   = $full->getBranding('plugin-url', $plugins[$key]['PluginURI']);
  $plugins[$key]['Description'] = $full->getBranding('plugin-description', $plugins[$key]['Description']);
  $plugins[$key]['Author']      = $full->getBranding('plugin-author', $plugins[$key]['Author']);
  $plugins[$key]['AuthorName']  = $full->getBranding('plugin-author', $plugins[$key]['AuthorName']);
  $plugins[$key]['AuthorURI']   = $full->getBranding('plugin-author-url', $plugins[$key]['AuthorURI']);

  return $plugins;
}

function pluginRowMeta(array $meta, string $plugin): array
{
  if ($plugin !== plugin_basename(FULL_CUSTOMER_FILE)) :
    return $meta;
  endif;

  $full = fullCustomer();

  if ($full->getBranding('plugin-author', '') === '') :
    return $meta;
  endif;

  foreach ($meta as $key => $action) :
    if (strpos($action, 'open-plugin-details-modal') !== false) :
      unset($meta[$key]);
    endif;
  endforeach;

  $pageUrl = esc_url(admin_url('options-general.php?page=full-connection'));

  $meta[] = '<a href="' . $pageUrl .  '">Configurações</a>';
  $meta[] = isFullConnected() ? 'Site conectado!' : '<a href="' . $pageUrl . '">Conectar site</a>';

  return $meta;
}

function notifyPluginError(array $args, array $error): array
{
  if (strpos($error['file'], dirname(FULL_CUSTOMER_FILE)) === false) :
    return $args;
  endif;

  $error['date'] = current_time('Y-m-d H:i:s');

  update_option('full_customer_last_error', $error, false);

  return $args;
}
