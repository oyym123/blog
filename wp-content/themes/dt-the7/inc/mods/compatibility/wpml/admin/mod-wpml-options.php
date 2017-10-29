<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Page definition.
 */
$options[] = array(
	"page_title" => _x( "WPML Flags", 'theme-options', 'the7mk2' ),
	"menu_title" => _x( "WPML Flags", 'theme-options', 'the7mk2' ),
	"menu_slug" => "of-wpml-menu",
	"type" => "page"
);

/**
 * Heading definition.
 */
$options[] = array( "name" => _x('WPML Flags', 'theme-options', 'the7mk2'), "type" => "heading" );

/**
 * WPML settings.
 */
$options[] = array( "name" => _x("WPML Flags", "theme-options", 'the7mk2'), "type" => "block_begin" );

	// checkbox
	$options['wpml_dt-custom_style'] = array(
		"name"      => _x( 'Use The7 skin for the language switcher', 'theme-options', 'the7mk2' ),
		"id"    	=> 'wpml_dt-custom_style',
		"type"  	=> 'checkbox',
		'std'   	=> 1,
	);
	$options[] = array(
		'desc' => _x( 'Click "Save" and configure language switcher appearance ' . sprintf(_x('<a href="%1$s">here</a>', 'theme-options', 'the7mk2'), admin_url( 'admin.php?page=sitepress-multilingual-cms%2Fmenu%2Flanguages.php#wpml-language-switcher-shortcode-action' ) ) . '.', 'theme-options', 'the7mk2' ),
		'type' => 'info',
	);


$options[] = array( "type" => "block_end" );
