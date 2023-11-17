<?php

namespace Full\Customer\Code;

defined('ABSPATH') || exit;

class Settings
{
  const PREFIX = 'code-';

  public function set(string  $prop, $value): void
  {
    fullCustomer()->set(self::PREFIX . $prop, $value);
  }

  public function get(string  $prop)
  {
    if ('robots' === $prop) :
      return file_exists(ABSPATH . '/robots.txt') ? file_get_contents(ABSPATH . '/robots.txt') : '';
    endif;

    return fullCustomer()->get(self::PREFIX . $prop);
  }

  public function getSections(): array
  {
    return [
      [
        'name' => 'CSS para frontend',
        'key'  => 'frontend-css',
        'mode' => 'css',
        'callback' => 'update-code',
        'instructions' => 'Estilos CSS que serão carregados apenas na área frontend do site.'
      ],
      [
        'name' => 'CSS para wp-admin',
        'key'  => 'admin-css',
        'mode' => 'css',
        'callback' => 'update-code',
        'instructions' => 'Estilos CSS que serão carregados apenas na área wp-admin do site.'
      ],
      [
        'name' => 'Códigos no &lt;head&gt;',
        'key'  => 'head-code',
        'mode' => 'htmlmixed',
        'callback' => 'update-code',
        'instructions' => 'Códigos que serão inseridos dentro da tag head do site. Útil para inserir código de track do Google Analytics, Meta e etc.'
      ],
      [
        'name' => 'Códigos no &lt;body&gt;',
        'key'  => 'body-code',
        'mode' => 'htmlmixed',
        'callback' => 'update-code',
        'instructions' => 'Códigos que serão inseridos no começo da tag body do site. Útil para inserir noscripts do Google Analytics, por exemplo.'
      ],
      [
        'name' => 'Códigos no &lt;footer&gt;',
        'key'  => 'footer-code',
        'mode' => 'htmlmixed',
        'callback' => 'update-code',
        'instructions' => 'Códigos que serão inseridos ao final da tag body do site. Útil para inserir scripts ou bibliotecas de javascript'
      ],
      [
        'name' => 'Robots.txt',
        'key'  => 'robots',
        'mode' => 'markdown',
        'callback' => 'update-robots',
        'instructions' => 'Aqui você verificar e editar facilmente o conteúdo do seu arquivo robots.txt. Caso ele não exista, será criado para você automaticamente.'
      ]
    ];
  }
}
