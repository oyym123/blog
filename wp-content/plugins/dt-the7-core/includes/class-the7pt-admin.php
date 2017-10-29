<?php
/**
 * Plugin admin class.
 */
class The7PT_Admin {

	/**
	 * Setup plugin admin part.
	 */
	public static function setup() {
		// Rename theme options.
		add_filter( 'presscore_options_menu_config', array( __CLASS__, 'rename_blog_options_menu_entry' ), 20 );

		// Add modules options.
		add_filter( 'presscore_options_files_to_load', array( __CLASS__, 'add_module_options' ) );

		// Flush rewrite rules after options save.
		add_action( 'admin_init', array( __CLASS__, 'flush_rewrite_rules_on_modules_switch' ), 20 );

		// Add plugin action links only for the7 theme.
		$plugin_basename = The7PT()->plugin_basename();
		add_action( "plugin_action_links_{$plugin_basename}", array( __CLASS__, 'add_plugin_action_links' ) );
	}

	/**
	 * Rename Blog theme options menu entry.
	 *
	 * @param array $menu_items
	 *
	 * @return array
	 */
	public static function rename_blog_options_menu_entry( $menu_items = array() ) {
		$menu_slug = 'of-blog-and-portfolio-menu';
		if ( array_key_exists( $menu_slug, $menu_items ) ) {
			$menu_items[ $menu_slug ] = array(
				'menu_title' => _x( 'Post Types', 'backend', 'dt-the7-core' ),
			);
		}

		return $menu_items;
	}

	/**
	 * Add plugin specific theme options.
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	public static function add_module_options( $options = array() ) {
		if ( array_key_exists( 'of-blog-and-portfolio-menu', $options ) ) {
			$options[ 'dt-the7-core-inject-modules-options' ] = The7PT()->plugin_path() . 'includes/theme-options/modules.php';
		}

		return $options;
	}

	/**
	 * Flush rewrite rules after modules switch.
	 */
	public static function flush_rewrite_rules_on_modules_switch() {
		$set = get_settings_errors( 'options-framework' );
		if ( $set && isset( $_GET['page'] ) && 'of-modules-menu' === $_GET['page'] ) {
			flush_rewrite_rules();
		}
	}

	/**
	 * Add plugin action links.
	 *
	 * @param array $links
	 *
	 * @return array
	 */
	public static function add_plugin_action_links( $links = array() ) {
		if ( defined( 'PRESSCORE_THEME_NAME' ) && current_user_can( 'edit_theme_options' ) ) {
			$links['the7pt_modules'] = '<a href="' . esc_url( 'admin.php?page=of-blog-and-portfolio-menu#admin-options-group-5' ) . '">' . __( 'Settings', 'dt-the7-core' ) . '</a>';
		}

		return $links;
	}
}