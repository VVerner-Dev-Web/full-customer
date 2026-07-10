<?php

namespace FC\Agents;

class AgentFactory
{
  public static function create(array $pluginData): AbstractAgent
  {
    $slug = $pluginData['slug'];

    // Mapeia o slug do plugin para classes específicas de agente se existirem.
    // Ex: elementor-pro -> FC\Agents\ElementorProAgent
    $className = 'FC\\Agents\\' . str_replace(' ', '', ucwords(str_replace('-', ' ', $slug))) . 'Agent';

    if (class_exists($className)) {
      return new $className($pluginData);
    }

    return new PluginAgent($pluginData);
  }
}
