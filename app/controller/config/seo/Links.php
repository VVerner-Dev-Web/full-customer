<?php

namespace Full\Customer\Seo;

defined('ABSPATH') || exit;

class Links
{
  public Settings $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();
    $cls = new self($env);

    if ($env->get('redirect404ToHomepage')) :
      add_filter('wp', [$cls, 'redirect404']);
    endif;

    if ($env->get('openExternalLinkInNewTab')) :
      add_filter('the_content', [$cls, 'updateExternalLinks']);
    endif;
  }

  public function redirect404(): void
  {
    if (!is_404() || is_admin() || defined('DOING_CRON') && DOING_CRON || defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) {
      return;
    } elseif (is_404()) {
      header('HTTP/1.1 301 Moved Permanently');
      header('Location: ' . site_url());
      exit;
    }
  }

  public function updateExternalLinks(string $content): string
  {
    if (!empty($content)) {
      // regex pattern for "a href"
      $regexp = "<a\\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>";

      if (preg_match_all(
        "/{$regexp}/siU",
        $content,
        $matches,
        PREG_SET_ORDER
      )) {
        // $matches might contain parts of $content that has links (a href)
        preg_match_all(
          "/{$regexp}/siU",
          $content,
          $matches,
          PREG_SET_ORDER
        );

        if (is_array($matches)) {
          $i = 0;
          foreach ($matches as $match) {
            $original_tag = $match[0];
            // e.g. <a title="Link Title" href="http://www.example.com/sit-quaerat">
            $tag = $match[0];
            // Same value as $original_tag but for further processing
            $url = $match[2];
            // e.g. http://www.example.com/sit-quaerat

            if (false !== strpos($url, get_site_url())) {
              // Internal link. Do nothing.
            } elseif (false === strpos($url, 'http')) {
              // Relative link to internal URL. Do nothing.
            } else {
              // External link. Let's do something.
              // Regex pattern for target="_blank|parent|self|top"
              $pattern = '/target\\s*=\\s*"\\s*_(blank|parent|self|top)\\s*"/';
              // If there's no 'target="_blank|parent|self|top"' in $tag, add target="blank"
              if (0 === preg_match($pattern, $tag)) {
                // Replace closing > with ' target="_blank">'
                $tag = substr_replace($tag, ' target="_blank">', -1);
              }
              // If there's no 'rel' attribute in $tag, add rel="noopener noreferrer nofollow"
              $pattern = '/rel\\s*=\\s*\\"[a-zA-Z0-9_\\s]*\\"/';

              if (0 === preg_match($pattern, $tag)) {
                // Replace closing > with ' rel="noopener noreferrer nofollow">'
                $tag = substr_replace($tag, ' rel="noopener noreferrer nofollow">', -1);
              } else {
                // replace rel="noopener" with rel="noopener noreferrer nofollow"
                if (false !== strpos($tag, 'noopener') && false === strpos($tag, 'noreferrer') && false === strpos($tag, 'nofollow')) {
                  $tag = str_replace('noopener', 'noopener noreferrer nofollow', $tag);
                }
              }

              // Replace original a href tag with one containing target and rel attributes above
              $content = str_replace($original_tag, $tag, $content);
            }

            $i++;
          }
        }
      }
    }

    return $content;
  }
}

Links::attach();
