<?php

namespace Full\Customer\Elementor;

use stdClass;

defined('ABSPATH') || exit;

class TemplateManager
{
  private static array $instances = [];

  protected function __clone()
  {
    throw new \Exception("Cannot clone a singleton.");
  }

  public function __wakeup()
  {
    throw new \Exception("Cannot wakeup a singleton.");
  }

  public static function instance(): self
  {
    $cls = static::class;
    if (!isset(self::$instances[$cls])) {
      self::$instances[$cls] = new static();
    }

    return self::$instances[$cls];
  }

  public function listPages(): array
  {
    return [
      (object) [
        'id'              => 1,
        'price'           => 10,
        'priceFormatted'  => 'R$ ' . number_format_i18n(10, 2),
        'priceTag'        => 'pro',
        'thumbnail'       => 'https://images.unsplash.com/photo-1472457897821-70d3819a0e24?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxleHBsb3JlLWZlZWR8Nnx8fGVufDB8fHx8&auto=format&fit=crop&w=500&h=700&q=60',
        'title'           => 'Modelo 1',
        'adminUrl'        => fullGetTemplatesUrl('single&item=1'),
        'description'     => 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Repudiandae itaque reiciendis, repellat eum autem soluta dignissimos fugiat voluptates facere, nisi quaerat rerum necessitatibus non odit velit doloribus recusandae! Neque, quaerat.',
        'type'            => 'page',
        'typeLabel'       => 'Página',
        'category'        => 1,
        'categoryLabel'   => 'Negócios',
        'canBeInstalled'  => true,
        'file'            => __DIR__ . '/templates/1.json'
      ],
      (object) [
        'id'              => 2,
        'price'           => 0,
        'priceFormatted'  => 'Grátis',
        'priceTag'        => 'free',
        'thumbnail'       => 'https://images.unsplash.com/photo-1612878010854-1250dfc5000a?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxleHBsb3JlLWZlZWR8NXx8fGVufDB8fHx8&auto=format&fit=crop&w=500&h=700&q=60',
        'title'           => 'Modelo 2',
        'adminUrl'        => fullGetTemplatesUrl('single&item=2'),
        'description'     => 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Repudiandae itaque reiciendis, repellat eum autem soluta dignissimos fugiat voluptates facere, nisi quaerat rerum necessitatibus non odit velit doloribus recusandae! Neque, quaerat.',
        'type'            => 'page',
        'typeLabel'       => 'Página',
        'category'        => 1,
        'categoryLabel'   => 'e-commerce',
        'canBeInstalled'  => true,
        'file'            => __DIR__ . '/templates/2.json'
      ],
      (object) [
        'id'              => 3,
        'price'           => 15,
        'priceFormatted'  => 'R$ ' . number_format_i18n(15, 2),
        'priceTag'        => 'pro',
        'thumbnail'       => 'https://images.unsplash.com/photo-1596920566403-2072ed25d7f5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxleHBsb3JlLWZlZWR8MTh8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&h=700&q=60',
        'title'           => 'Modelo 3',
        'adminUrl'        => fullGetTemplatesUrl('single&item=3'),
        'description'     => 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Repudiandae itaque reiciendis, repellat eum autem soluta dignissimos fugiat voluptates facere, nisi quaerat rerum necessitatibus non odit velit doloribus recusandae! Neque, quaerat.',
        'type'            => 'page',
        'typeLabel'       => 'Página',
        'category'        => 1,
        'categoryLabel'   => 'Advocacia',
        'canBeInstalled'  => false,
        'file'            => null
      ]
    ];
  }

  public function getItem(int $itemId): ?stdClass
  {
    $item = array_filter($this->listPages(), fn ($item) => $item->id === $itemId);
    return $item ? array_shift($item) : null;
  }
}
