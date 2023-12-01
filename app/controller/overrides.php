<?php defined('ABSPATH') || exit;

function wp_sanitize_redirect($location)
{
  $location = str_replace(' ', '%20', $location);

  $regex    = '/
	(
		(?: [\xC2-\xDF][\x80-\xBF]        # double-byte sequences   110xxxxx 10xxxxxx
		|   \xE0[\xA0-\xBF][\x80-\xBF]    # triple-byte sequences   1110xxxx 10xxxxxx * 2
		|   [\xE1-\xEC][\x80-\xBF]{2}
		|   \xED[\x80-\x9F][\x80-\xBF]
		|   [\xEE-\xEF][\x80-\xBF]{2}
		|   \xF0[\x90-\xBF][\x80-\xBF]{2} # four-byte sequences   11110xxx 10xxxxxx * 3
		|   [\xF1-\xF3][\x80-\xBF]{3}
		|   \xF4[\x80-\x8F][\x80-\xBF]{2}
	){1,40}                              # ...one or more times
	)/x';
  $location = preg_replace_callback($regex, '_wp_sanitize_utf8_in_redirect', $location);
  $location = preg_replace('|[^a-z0-9-~+_.?#=&;,/:%!*\[\]()@]|i', '', $location);
  $location = wp_kses_no_null($location);

  $strip = array('%0d', '%0D');
  return _deep_replace($strip, $location);
}

function _wp_sanitize_utf8_in_redirect($matches)
{
  return urlencode($matches[0]);
}
