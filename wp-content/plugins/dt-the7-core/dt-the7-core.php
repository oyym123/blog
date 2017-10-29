<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           dt_the7_core
 *
 * @wordpress-plugin
 * Plugin Name:       The7 Post Types
 * Description:       This plugin contains The7 custom post types and corresponding Visual Composer elements.
 * Version:           1.2.0
 * Author:            Dream-Theme
 * Author URI:        http://dream-theme.com/
 * Text Domain:       dt-the7-core
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-the7pt-activator.php
 */
function activate_The7PT() {
	require_once 'includes/class-the7pt-activator.php';
	The7PT_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-the7pt-deactivator.php
 */
function deactivate_The7PT() {
	require_once 'includes/class-the7pt-deactivator.php';
	The7PT_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_The7PT' );
register_deactivation_hook( __FILE__, 'deactivate_The7PT' );

if ( ! class_exists( 'The7PT_Core' ) ) :

	final class The7PT_Core {

		/**
		 * Plugin version.
		 *
		 * @var string
		 */
		private $version = '1.2.0';

		/**
		 * The single instance of the class.
		 *
		 * @var The7PT_Core
		 */
		private static $_instance = null;

		/**
		 * Main plugin instance.
		 *
		 * @return The7PT_Core
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Cloning is forbidden.
		 */
		private function __clone() {
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 */
		private function __wakeup() {
		}

		/**
		 * The7PT_Core constructor.
		 */
		public function __construct() {
			$this->load_dependencies();
			$this->init_hooks();
		}

		/**
		 * Load plugin dependencies.
		 *
		 * @since 1.0.0
		 */
		private function load_dependencies() {
			require_once 'includes/class-the7pt-modules.php';
			require_once 'includes/class-the7pt-assets.php';
			require_once 'includes/class-the7pt-admin.php';
		}

		/**
		 * Define admin hooks.
		 *
		 * @since 1.0.0
		 */
		private function init_hooks() {
			// Do it after setup theme because some strings used before init hook.
			add_action( 'after_setup_theme', array( $this, 'load_plugin_textdomain' ), 5 );
			add_action( 'plugins_loaded', array( 'The7PT_Modules', 'setup' ) );
			add_action( 'after_setup_theme', array( 'The7PT_Assets', 'setup' ), 20 );
			add_action( 'plugins_loaded', array( 'The7PT_Admin', 'setup' ) );
		}

		/**
		 * Load plugin text domain.
		 *
		 * @since 1.1.1
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'dt-the7-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Returns plugin url. With trailing slash.
		 *
		 * @return string
		 */
		public function plugin_url() {
			return plugins_url( '/', __FILE__ );
		}

		/**
		 * Returns plugin path. With trailing slash.
		 *
		 * @return string
		 */
		public function plugin_path() {
			return plugin_dir_path( __FILE__ );
		}

		/**
		 * Returns plugin base name.
		 *
		 * @return string
		 */
		public function plugin_basename() {
			return plugin_basename( __FILE__ );
		}

		/**
		 * Returns plugin version.
		 *
		 * @return string
		 */
		public function version() {
			return $this->version;
		}
	}

endif;

function The7PT() {
	return The7PT_Core::instance();
}

The7PT();