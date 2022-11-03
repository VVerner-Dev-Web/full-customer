<?php

namespace Full\Customer\Filters;

use FullCustomer;

defined('ABSPATH') || exit;

function versionsWithUpgrade(array $versions): array
{
  $versions[] = '0.0.9';
  $versions[] = '0.1.1';

  return $versions;
}

function setPluginBranding(array $plugins): array
{
  $key    = plugin_basename(FULL_CUSTOMER_FILE);

  if (!isset($plugins[$key])) :
    return $plugins;
  endif;

  $full = new FullCustomer();

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

  $full = new FullCustomer();

  if ($full->getBranding('plugin-author', '') === '') :
    return $meta;
  endif;

  foreach ($meta as $key => $action) :
    if (strpos($action, 'open-plugin-details-modal') !== false) :
      unset($meta[$key]);
    endif;
  endforeach;

  $pageUrl =  add_query_arg(['page' => 'full-connection'], admin_url('options-general.php'));

  $meta[] = '<a href="' . $pageUrl .  '">Configurações</a>';
  $meta[] = isSiteConnectedOnFull() ? 'Site conectado!' : '<a href="' . $pageUrl . '">Conectar site</a>';

  return $meta;
}
