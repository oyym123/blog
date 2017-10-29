<?php
/**
 * Theme update page.

 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( presscore_theme_is_activated() ) {
	$options[] = array(
		'desc'  => _x( '<strong>Your copy of The7 is registered!</strong><br>You have access to automatic theme updates, premium plugins, pre-made websites, etc.', 'theme-options', 'the7mk2' ),
		'type'  => 'info',
		'class' => 'green-border',
	);
}

/**
 * Heading definition.
 */
$options[] = array( 'name' => _x( 'Theme Activation', 'theme-options', 'the7mk2' ), 'type' => 'heading' );

$options[] = array( 'name' => _x( 'User credentials', 'theme-options', 'the7mk2' ), 'type' => 'block' );

$options[] = array(
	'name'     => _x( 'Purchase Code', 'theme-options', 'the7mk2' ),
	'id'       => 'theme_update-purchase_code',
	'std'      => '',
	'type'     => 'text',
	'disabled' => presscore_theme_is_activated(),
);
