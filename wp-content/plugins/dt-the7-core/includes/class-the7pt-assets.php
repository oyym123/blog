<?php

/**
 * Assets class.
 * @since       1.0.0
 * @package     dt_the7_core
 */
class The7PT_Assets {

	/**
	 * Setup assets.
	 */
	public static function setup() {
		if ( ! defined( 'PRESSCORE_STYLESHEETS_VERSION' ) || version_compare( PRESSCORE_STYLESHEETS_VERSION, '3.7.0' ) < 0 ) {
			return;
		}

		// Enqueue plugin styles and scripts.
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ), 17 );

		// Register dynamic stylesheets.
		add_filter( 'presscore_get_dynamic_stylesheets_list', array( __CLASS__, 'register_dynamic_stylesheet' ) );
	}

	/**
	 * Enqueue scripts.
	 */
	public static function enqueue_scripts() {
		wp_enqueue_script( 'the7pt', trailingslashit( get_template_directory_uri() ) . 'js/post-type.js', array(), wp_get_theme()->get( 'Version' ), true );
		wp_enqueue_style( 'the7pt-static', trailingslashit( get_template_directory_uri() ) . 'css/post-type.css', array(), wp_get_theme()->get( 'Version' ) );
	}

	/**
	 * Register dynamic stylesheets.
	 *
	 * @param array $dynamic_stylesheets
	 *
	 * @return array
	 */
	public static function register_dynamic_stylesheet( $dynamic_stylesheets ) {
		$dynamic_stylesheets['the7pt.less'] = array(
			'path' => trailingslashit( get_template_directory() ) . 'css/post-type-dynamic.less',
			'src' => trailingslashit( get_template_directory_uri() ) . 'css/post-type-dynamic.less',
			'fallback_src' => false,
			'deps' => array(),
			'ver' => wp_get_theme()->get( 'Version' ),
			'media' => 'all'
		);

		return $dynamic_stylesheets;
	}
}