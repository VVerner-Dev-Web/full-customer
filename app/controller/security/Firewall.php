<?php

namespace Full\Customer\Security;

class Firewall
{
  private const REQUEST_MAX_LENGTH = 2000;

  private static function requestUriItems(): array
  {
    return [
      '\/\.env',
      '\s',
      '<',
      '>',
      '\^',
      '`',
      '@@',
      '\?\?',
      '\/&&',
      '\\',
      '\/=',
      '\/:\/',
      '\/\/\/',
      '\.\.\.',
      '\/\*(.*)\*\/',
      '\+\+\+',
      '\{0\}',
      '0x00',
      '%00',
      '\(\/\(',
      '(\/|;|=|,)nt\.',
      '@eval',
      'eval\(',
      'union(.*)select',
      '\(null\)',
      'base64_',
      '(\/|%2f)localhost',
      '(\/|%2f)pingserver',
      'wp-config\.php',
      '(\/|\.)(s?ftp-?)?conf(ig)?(uration)?\.',
      '\/wwwroot',
      '\/makefile',
      'crossdomain\.',
      'self\/environ',
      'usr\/bin\/perl',
      'var\/lib\/php',
      'etc\/passwd',
      '\/https:',
      '\/http:',
      '\/ftp:',
      '\/file:',
      '\/php:',
      '\/cgi\/',
      '\.asp',
      '\.bak',
      '\.bash',
      '\.bat',
      '\.cfg',
      '\.cgi',
      '\.cmd',
      '\.conf',
      '\.db',
      '\.dll',
      '\.ds_store',
      '\.exe',
      '\/\.git',
      '\.hta',
      '\.htp',
      '\.init?',
      '\.jsp',
      '\.mysql',
      '\.pass',
      '\.pwd',
      '\.sql',
      '\/\.svn',
      '\.exec\(',
      '\)\.html\(',
      '\{x\.html\(',
      '\.php\([0-9]+\)',
      '(benchmark|sleep)(\s|%20)*\(',
      '\/(db|mysql)-?admin',
      '\/document_root',
      '\/error_log',
      'indoxploi',
      '\/sqlpatch',
      'xrumer',
      'www\.(.*)\.cn',
      '%3Cscript',
      '\/vbforum(\/)?',
      '\/vbulletin(\/)?',
      '\{\$itemURL\}',
      '(\/bin\/)(cc|chmod|chsh|cpp|echo|id|kill|mail|nasm|perl|ping|ps|python|tclsh)(\/)?$',
      '((curl_|shell_)?exec|(f|p)open|function|fwrite|leak|p?fsockopen|passthru|phpinfo|posix_(kill|mkfifo|setpgid|setsid|setuid)|proc_(close|get_status|nice|open|terminate)|system)(.*)(\()(.*)(\))',
      '(\/)(^$|0day|c99|configbak|curltest|db|index\.php\/index|(my)?sql|(php|web)?shell|php-?info|temp00|vuln|webconfig)(\.php)'
    ];
  }

  private static function queryStringItems(): array
  {
    return [
      '\(0x',
      '0x3c62723e',
      ';!--=',
      '\(\)\}',
      ':;\};',
      '\.\.\/',
      '\/\*\*\/',
      '127\.0\.0\.1',
      'localhost',
      'loopback',
      '%0a',
      '%0d',
      '%00',
      '%2e%2e',
      '%0d%0a',
      '@copy',
      'concat(.*)(\(|%28)',
      'allow_url_(fopen|include)',
      '(c99|php|web)shell',
      'auto_prepend_file',
      'disable_functions?',
      'gethostbyname',
      'input_file',
      'execute',
      'safe_mode',
      'file_(get|put)_contents',
      'mosconfig',
      'open_basedir',
      'outfile',
      'proc_open',
      'root_path',
      'user_func_array',
      'path=\.',
      'mod=\.',
      '(globals|request)(=|\[)',
      'f(fclose|fgets|fputs|fsbuff)',
      '\$_(env|files|get|post|request|server|session)',
      '(\+|%2b)(concat|delete|get|select|union)(\+|%2b)',
      '(cmd|command)(=|%3d)(chdir|mkdir)',
      '(absolute_|base|root_)(dir|path)(=|%3d)(ftp|https?)',
      '(s)?(ftp|inurl|php)(s)?(:(\/|%2f|%u2215)(\/|%2f|%u2215))',
      '(\/|%2f)(=|%3d|\$&|_mm|cgi(\.|-)|inurl(:|%3a)(\/|%2f)|(mod|path)(=|%3d)(\.|%2e))',
      '(<|>|\'|")(.*)(\/\*|alter|base64|benchmark|cast|char|concat|convert|create|declare|delete|drop|encode|exec|fopen|function|html|insert|md5|request|script|select|set|union|update)'
    ];
  }

  private static function userAgentItems(): array
  {
    return [
      '&lt;',
      '%0a',
      '%0d',
      '%27',
      '%3c',
      '%3e',
      '%00',
      '0x00',
      '\/bin\/bash',
      '360Spider',
      'acapbot',
      'acoonbot',
      'alexibot',
      'asterias',
      'attackbot',
      'backdorbot',
      'base64_decode',
      'becomebot',
      'binlar',
      'blackwidow',
      'blekkobot',
      'blexbot',
      'blowfish',
      'bullseye',
      'bunnys',
      'butterfly',
      'careerbot',
      'casper',
      'checkpriv',
      'cheesebot',
      'cherrypick',
      'chinaclaw',
      'choppy',
      'clshttp',
      'cmsworld',
      'copernic',
      'copyrightcheck',
      'cosmos',
      'crescent',
      'cy_cho',
      'datacha',
      'demon',
      'diavol',
      'discobot',
      'disconnect',
      'dittospyder',
      'dotbot',
      'dotnetdotcom',
      'dumbot',
      'emailcollector',
      'emailsiphon',
      'emailwolf',
      'eval\(',
      'exabot',
      'extract',
      'eyenetie',
      'feedfinder',
      'flaming',
      'flashget',
      'flicky',
      'foobot',
      'g00g1e',
      'getright',
      'gigabot',
      'go-ahead-got',
      'gozilla',
      'grabnet',
      'grafula',
      'harvest',
      'heritrix',
      'httrack',
      'icarus6j',
      'jetbot',
      'jetcar',
      'jikespider',
      'kmccrew',
      'leechftp',
      'libweb',
      'linkextractor',
      'linkscan',
      'linkwalker',
      'loader',
      'lwp-download',
      'masscan',
      'miner',
      'majestic',
      'md5sum',
      'mechanize',
      'mj12bot',
      'morfeus',
      'moveoverbot',
      'netmechanic',
      'netspider',
      'nicerspro',
      'nikto',
      'nutch',
      'octopus',
      'pagegrabber',
      'planetwork',
      'postrank',
      'proximic',
      'purebot',
      'pycurl',
      'queryn',
      'queryseeker',
      'radian6',
      'radiation',
      'realdownload',
      'remoteview',
      'rogerbot',
      'scooter',
      'seekerspider',
      'semalt',
      '(c99|php|web)shell',
      'shellshock',
      'siclab',
      'sindice',
      'sistrix',
      'sitebot',
      'site(.*)copier',
      'siteexplorer',
      'sitesnagger',
      'skygrid',
      'smartdownload',
      'snoopy',
      'sosospider',
      'spankbot',
      'spbot',
      'sqlmap',
      'stackrambler',
      'stripper',
      'sucker',
      'surftbot',
      'sux0r',
      'suzukacz',
      'suzuran',
      'takeout',
      'teleport',
      'telesoft',
      'true_robots',
      'turingos',
      'turnit',
      'unserialize',
      'vampire',
      'vikspider',
      'voideye',
      'webleacher',
      'webreaper',
      'webstripper',
      'webvac',
      'webviewer',
      'webwhacker',
      'winhttp',
      'wwwoffle',
      'woxbot',
      'xaldon',
      'xxxyy',
      'yamanalab',
      'yioopbot',
      'youda',
      'zeus',
      'zmeu',
      'zyborg'
    ];
  }

  private static function referrerItems(): array
  {
    return [
      'blue\s?pill',
      'ejaculat',
      'erectile',
      'erections',
      'hoodia',
      'huronriver',
      'impotence',
      'levitra',
      'libido',
      'lipitor',
      'phentermin',
      'pro[sz]ac',
      'sandyauer',
      'semalt\.com',
      'todaperfeita',
      'tramadol',
      'ultram',
      'unicauca',
      'valium',
      'viagra',
      'vicodin',
      'xanax',
      'ypxaieo'
    ];
  }

  private static function postItems(): array
  {
    return [
      '<%=',
      '\+\/"\/\+\/',
      '(<|%3C|&lt;?|u003c|x3c)script',
      'src=#\s',
      '(href|src)="javascript:',
      '(href|src)=javascript:',
      '(href|src)=`javascript:'
    ];
  }

  public static function run(): void
  {
    if (!(fullCustomer())->isServiceEnabled('full-security')) :
      return;
    endif;

    if (is_admin() && current_user_can('edit_posts')) :
      return;
    endif;

    $request_uri_string = isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $query_string_string = isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
    $user_agent_string = isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $referrer_string = isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

    $matches = [];

    if (strlen($request_uri_string) > self::REQUEST_MAX_LENGTH || strlen($referrer_string) > self::REQUEST_MAX_LENGTH) :
      error_log(__LINE__);
      self::response([self::REQUEST_MAX_LENGTH]);
    endif;

    if ($request_uri_string && self::preg_match_array($request_uri_string, self::requestUriItems(), $matches)) :
      error_log(__LINE__);
      self::response($matches);
    endif;

    if ($query_string_string && self::preg_match_array($query_string_string, self::queryStringItems(), $matches)) :
      error_log(__LINE__);
      self::response($matches);
    endif;

    if ($user_agent_string && self::preg_match_array($user_agent_string, self::userAgentItems(), $matches)) :
      error_log(__LINE__);
      self::response($matches);
    endif;

    if ($referrer_string && self::preg_match_array($referrer_string, self::referrerItems(), $matches)) :
      error_log(__LINE__);
      self::response($matches);
    endif;

    if (isset($_POST) && $_POST) :
      foreach ($_POST as $value) :
        $value = self::getString($value);

        if (empty($value)) {
          continue;
        }

        if (self::preg_match_array($value, self::postItems(), $matches)) {
          error_log(__LINE__);
          self::response($matches);
          break;
        }
      endforeach;
    endif;
  }

  private static function response(array $errors): void
  {
    do_action('full/firewall', $errors);

    $error = array_shift($errors);

    if ($error) :
      error_log('[FULL FIREWALL] ' . $error);
    endif;

    header('Protected-By: FULL.');
    header('HTTP/1.1 403 Forbidden');
    header('Status: 403 Forbidden');
    header('Connection: Close');

    exit;
  }

  private static function preg_match_array(string $subject, array $pattern, array &$matches)
  {
    $pattern = '/' . implode('|', $pattern) . '/i';
    return preg_match($pattern, $subject, $matches);
  }

  private static function getString($var)
  {
    if (!is_array($var)) :
      return $var;
    endif;

    foreach ($var as $value) {
      if (is_array($value)) {
        self::getString($value);
      } else {
        return $value;
      }
    }
  }
}
