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
      return file_exists(ABSPATH . '/robots.txt') ? fullFileSystem()->get_contents(ABSPATH . '/robots.txt') : '';
    endif;

    return fullCustomer()->get(self::PREFIX . $prop);
  }

  public function getConst(string $const)
  {
    return defined($const) ? constant($const) : null;
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

  public function enableWpDebug(): void
  {
    if ($this->getConst('WP_DEBUG')) :
      return;
    endif;

    $this->switchConstValue('WP_DEBUG', 'true', 'false');
  }

  public function disableWpDebug(): void
  {
    if (!$this->getConst('WP_DEBUG')) :
      return;
    endif;

    $this->switchConstValue('WP_DEBUG', 'false', 'true');
  }

  private function switchConstValue(string $constName, string $switchTo, string $switchFrom): void
  {
    $code = "define('$constName', $switchTo)";

    $content = $this->getWpConfigContent();
    $content = str_replace(
      ['define("' . $constName . '", ' . $switchFrom . ')', "define('$constName', $switchFrom)"],
      [$code, $code],
      $content
    );

    if (strpos($content, $code) === false) :
      $content = preg_replace("/^([\r\n\t ]*)(\<\?)(php)?/i", "<?php " . PHP_EOL . "$code; " . PHP_EOL, $content);
    endif;

    $this->setWpConfigContent($content);
  }

  private function getWpConfigContent(): string
  {
    $filename = ABSPATH . 'wp-config.php';
    return fullFileSystem()->is_writable($filename) ? fullFileSystem()->get_contents($filename) : '';
  }

  private function setWpConfigContent(string $content): bool
  {
    $done = false;
    $filename = ABSPATH . 'wp-config.php';

    if (fullFileSystem()->is_writable($filename)) :
      $done = fullFileSystem()->put_contents($filename, $content);
    endif;

    return (bool) $done;
  }

  public function enableWpDebugLog(): void
  {
    if ($this->getConst('WP_DEBUG_LOG')) :
      return;
    endif;

    $this->enableWpDebug();
    $this->switchConstValue('WP_DEBUG_LOG', 'true', 'false');
  }

  public function disableWpDebugLog(): void
  {
    if (!$this->getConst('WP_DEBUG_LOG')) :
      return;
    endif;

    $this->switchConstValue('WP_DEBUG_LOG', 'false', 'true');
  }

  public function enableWpDebugDisplay(): void
  {
    if ($this->getConst('WP_DEBUG_DISPLAY')) :
      return;
    endif;

    $this->enableWpDebug();
    $this->switchConstValue('WP_DEBUG_DISPLAY', 'true', 'false');
  }

  public function disableWpDebugDisplay(): void
  {
    if (!$this->getConst('WP_DEBUG_DISPLAY')) :
      return;
    endif;

    $this->switchConstValue('WP_DEBUG_DISPLAY', 'false', 'true');
  }
}
