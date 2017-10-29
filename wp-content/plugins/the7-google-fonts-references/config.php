<?php if (!defined('ABSPATH')) exit;

$remove_google_fonts_configs = NULL;
function remove_google_fonts_get_config($key)
{
	global $remove_google_fonts_configs;

	if ($remove_google_fonts_configs === NULL) {
		$remove_google_fonts_configs = get_option('remove_google_fonts_configs');
	}
	if ($key && $remove_google_fonts_configs && isset($remove_google_fonts_configs[$key])) {
		if ( !empty($remove_google_fonts_configs[$key.'_expire_at'])
				&& (intval($remove_google_fonts_configs[$key.'_expire_at'])<time()) ) {
			return FALSE;
		}
		return $remove_google_fonts_configs[$key];
	}
	return FALSE;
}
