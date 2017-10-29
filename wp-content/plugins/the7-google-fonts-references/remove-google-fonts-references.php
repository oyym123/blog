<?php if (!defined('ABSPATH')) exit;
/*
Plugin Name: The7 Google Fonts References
Plugin URI: http://www.yundic.com
Description: Remove Open Sans and other google fonts references from all pages.
Author: Bruno Xu
Author URI: http://www.yundic.com/
Version: 2.6
License: GNU General Public License v2 or later
License URI: http://www.yundic.com
*/

define('REMOVE_GOOGLE_FONTS_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('REMOVE_GOOGLE_FONTS_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
define('REMOVE_GOOGLE_FONTS_PLUGIN_CACHE_URL', REMOVE_GOOGLE_FONTS_PLUGIN_URL.'cache/');
define('REMOVE_GOOGLE_FONTS_PLUGIN_CACHE_DIR', REMOVE_GOOGLE_FONTS_PLUGIN_DIR.'cache/');
if (! file_exists(REMOVE_GOOGLE_FONTS_PLUGIN_DIR)) mkdir(REMOVE_GOOGLE_FONTS_PLUGIN_DIR, 0755, true);

include_once REMOVE_GOOGLE_FONTS_PLUGIN_DIR.'config.php';


function remove_google_fonts_is_login_page() {
	return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}

if (is_admin()) {// init -> wp_loaded -> admin_menu -> admin_init -> wp -> admin_enqueue_scripts -> admin_head
	$action = 'admin_init'; // OK
	//$action = 'wp'; // NG
} elseif (remove_google_fonts_is_login_page()) {
	$action = 'wp_loaded'; // OK
	//$action = 'wp'; // NG
} else { // init -> wp_loaded -> wp -> template_redirect -> get_header -> wp_enqueue_scripts -> wp_head
	$action = 'template_redirect'; // OK
	//$action = 'get_header';// NG in theme 'pinnacle'(use redux framework)
}

add_action($action, 'remove_google_fonts_obstart', apply_filters('remove_google_fonts_priority', 11));
function remove_google_fonts_obstart() {
	ob_start('remove_google_fonts_obend');
}

function remove_google_fonts_obend($content) {
	return remove_google_fonts_filter($content);
}

function remove_google_fonts_filter($content)
{
	$content = apply_filters('remove_google_fonts_content_filter_before', $content);

	/*
	google fonts imported by 'Web Font Loader'
		https://ajax.googleapis．com/ajax/libs/webfont/1.5.3/webfont.js
		http://ajax.googleapis．com/ajax/libs/webfont/1/webfont.js
	*/
	$regexp = "|(http(s)?:)?//ajax.googleapis".".com/ajax/libs/webfont/[\d\.]+/webfont.js|i";
	$content = preg_replace_callback(
		$regexp,
		"remove_google_fonts_str_handler2",
		$content
	);

	/*
	<link rel="stylesheet" id="open-sans-css" href="//fonts.googleapis．com/css?family=Open+Sans%3A300italic%2C400italic%2C600italic%2C300%2C400%2C600&amp;subset=latin%2Clatin-ext&amp;ver=3.9.2" type="text/css" media="all">
	*/
	$regexp = "/<link([^<>]+)>/i";
	$content = preg_replace_callback(
		$regexp,
		"remove_google_fonts_str_handler",
		$content
	);

	/*
	@import url(http://fonts.googleapis．com/css?family=Roboto+Condensed:regular);
	@import url(http://fonts.googleapis．com/css?family=Merriweather:300,300italic,700,700italic);
	*/
	$regexp = "/@import\s+url\([^\(\)]+\);?/i";
	$content = preg_replace_callback(
		$regexp,
		"remove_google_fonts_str_handler",
		$content
	);


	/*
	inside css files like:
	http://www.xxxxxx.com/wp-content/plugins/pricing-table/css/site/tipTip.css?ver=4.0
		@import url("http://fonts.googleapis．com/css?family=Pathway+Gothic+One|Roboto+Slab");
	http://www.xxxxxx.com/wp-content/themes/voyager/framework/assets/admin/css/admin.css?ver=3.9.2
		@import url(http://fonts.googleapis．com/css?family=Open+Sans:400italic,300,400,700);
	*/
	$regexp = "/<link[^<>]+href=['\"]([^'\"]+)['\"][^<>]*>/i";

	global $remove_google_fonts_cssfiles;
	$remove_google_fonts_cssfiles = remove_google_fonts_get_config('cssfiles');
	if ($remove_google_fonts_cssfiles === FALSE) {
		$remove_google_fonts_cssfiles = array();

		preg_match_all($regexp, $content, $matches);

		if (!empty($matches) && !empty($matches[0])) {
			foreach ($matches[1] as $ind=>$match) {
				if (stripos($matches[0][$ind], 'stylesheet') === FALSE) {//'text/css'
					continue;
				}

				$cssfile = $match;

				remove_google_fonts_cache_cssfile($cssfile, $remove_google_fonts_cssfiles);
			}
		}

		global $remove_google_fonts_configs;
		//$remove_google_fonts_configs['cssfiles'.'_expire_at'] = time()+86400*180;//1.
		unset($remove_google_fonts_configs['cssfiles'.'_expire_at']);//2.
		$remove_google_fonts_configs['cssfiles'] = $remove_google_fonts_cssfiles;
		update_option('remove_google_fonts_configs', $remove_google_fonts_configs);
	}

	$content = preg_replace_callback(
		$regexp,
		"remove_google_fonts_css_file_handler",
		$content
	);

	return apply_filters('remove_google_fonts_content_filter_after', $content);
}

function remove_google_fonts_str_handler($matches)
{
	$str = $matches[0];

	if (! preg_match("/\/\/fonts.googleapis.com\//i", $str)) {
		return $str;
	} else {
		return '';
	}
}

function remove_google_fonts_str_handler2($matches)
{
	$str = $matches[0];

	$webfont_js = REMOVE_GOOGLE_FONTS_PLUGIN_URL . 'webfont_v1.5.3.js';

	$str = preg_replace('|//ajax.googleapis'.'.com/ajax/libs/webfont/[\d\.]+/webfont.js|i', substr($webfont_js, strpos($webfont_js,'//')), $str);

	if (is_ssl() && stristr($str, 'http://')) {
		$str = str_ireplace('http://', 'https://', $str);
	} elseif (!is_ssl() && stristr($str, 'https://')) {
		$str = str_ireplace('https://', 'http://', $str);
	}

	return $str;
}

function remove_google_fonts_css_file_handler($matches)
{
	global $remove_google_fonts_cssfiles;

	$str = $matches[0];
	$cssfile = $matches[1];

	if (stripos($str, 'stylesheet') === FALSE) {//'text/css'
		return $str;
	}

	if (empty($remove_google_fonts_cssfiles)) {
		return $str;
	}

	$key = strtolower(md5(strtolower($cssfile)));
	if (!isset($remove_google_fonts_cssfiles[$key])) {
		remove_google_fonts_cache_cssfile($cssfile, $remove_google_fonts_cssfiles);

		if (isset($remove_google_fonts_cssfiles[$key])) {
			global $remove_google_fonts_configs;
			$remove_google_fonts_configs['cssfiles'] = $remove_google_fonts_cssfiles;
			update_option('remove_google_fonts_configs', $remove_google_fonts_configs);
		}

		if (!empty($remove_google_fonts_cssfiles[$key])) {
			$new_cssfile = $remove_google_fonts_cssfiles[$key];
			return str_ireplace($cssfile, $new_cssfile, $str);
		}

		return $str;
	} elseif (!empty($remove_google_fonts_cssfiles[$key])) {
		$new_cssfile = $remove_google_fonts_cssfiles[$key];
		if ( !file_exists(REMOVE_GOOGLE_FONTS_PLUGIN_CACHE_DIR.basename($remove_google_fonts_cssfiles[$key])) ) {
			global $remove_google_fonts_configs;
			unset($remove_google_fonts_cssfiles[$key]);
			$remove_google_fonts_configs['cssfiles'] = $remove_google_fonts_cssfiles;
			update_option('remove_google_fonts_configs', $remove_google_fonts_configs);
			return $str;
		}
		return str_ireplace($cssfile, $new_cssfile, $str);
	} else {
		return $str;
	}
}

/* match all conditions | only match files in plugins and themes dir */
function remove_google_fonts_cache_cssfile($cssfile, &$remove_google_fonts_cssfiles)
{
	$regexp = "/@import\s+url\([^\(\)]+fonts.googleapis.com[^\(\)]+\);?/i";

	$key = strtolower(md5(strtolower($cssfile)));
	$remove_google_fonts_cssfiles[$key] = '';

	$pass = FALSE;
	if ($cssfile[0] == '/' && $cssfile[1] == '/') {
		$cssfile_fullpath = (is_ssl()?'https:':'http:') . $cssfile;
	} elseif ($cssfile[0] == '/') {
		$cssfile_fullpath = home_url() . $cssfile;
	} elseif (stripos($cssfile, 'http') === 0) {
		$cssfile_fullpath = $cssfile;
	} elseif ($cssfile[0] == '.') {
		$pass = TRUE;
	} else {
		$pass = TRUE;
	}
	if (!$pass && !empty($cssfile_fullpath)) {
		$filecontent = wp_remote_fopen($cssfile_fullpath);

		if ($filecontent === FALSE) {
			unset($remove_google_fonts_cssfiles[$key]);
		} else {
			if (preg_match($regexp, $filecontent)) {
				$filecontent = preg_replace($regexp, '', $filecontent);

				$filecontent = str_ireplace('data:image', '--data:image', $filecontent);
				$filecontent = preg_replace('/(url\(\s*[\'"]?)([0-9a-zA-Z\.])/i', '$1'.dirname($cssfile_fullpath).'/$2', $filecontent);
				$filecontent = str_ireplace('--data:image', 'data:image', $filecontent);

				$new_cssfile_path = REMOVE_GOOGLE_FONTS_PLUGIN_CACHE_DIR . $key . '.css';
				$new_cssfile_url = REMOVE_GOOGLE_FONTS_PLUGIN_CACHE_URL . $key . '.css';
				$handle = fopen($new_cssfile_path, 'w+');
				fwrite($handle, $filecontent);
				fclose($handle);
				
				if (file_exists($new_cssfile_path)) {
					$remove_google_fonts_cssfiles[$key] = $new_cssfile_url;
				} else {
					unset($remove_google_fonts_cssfiles[$key]);
				}
			}
		}
	}
}


function remove_google_fonts_activate() {
	add_option( 'remove_google_fonts_configs', array() );
}
register_activation_hook( __FILE__, 'remove_google_fonts_activate' );

function remove_google_fonts_uninstall() {
	delete_option( 'remove_google_fonts_configs' );
}
register_uninstall_hook( __FILE__, 'remove_google_fonts_uninstall' );
