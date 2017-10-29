<?php
/**
 * TGM plugin module.
 *
 * @package the7
 * @since 3.0.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Presscore_Modules_TGMPAModule', false ) ) :

	class Presscore_Modules_TGMPAModule {

		/**
		 * Execute module.
		 */
		public static function execute() {
			add_filter( 'pre_set_site_transient_update_plugins', array( __CLASS__, 'update_plugins_list' ) );

			if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
				return;
			}

			global $tgmpa;

			// Bail if $tgmpa already registered.
			if ( is_a( $tgmpa, 'TGM_Plugin_Activation' ) ) {
                return;
            }

			include_once 'class-tgm-plugin-activation.php';
			include_once 'class-the7-tgmpa.php';
            include_once 'class-the7-plugins-list-table.php';

			// Register plugins.
			add_action( 'tgmpa_register', array( __CLASS__, 'register_plugins_action' ) );
		}

		public static function register_plugins_action() {
			$plugins = self::get_plugins_list_cache();
			if ( ! $plugins ) {
				$plugins = self::get_update_plugin_list();
				if ( is_wp_error( $plugins ) ) {
					$plugins = include trailingslashit( PRESSCORE_DIR ) . 'plugins.php';
                }
            }

			$plugins = apply_filters( 'presscore_tgmpa_module_plugins_list', $plugins );

			tgmpa( $plugins, array(
				'id'               => 'the7_tgmpa',
				'menu'             => 'the7-plugins',
				'parent_slug'      => 'admin.php?page=the7-dashboard',
				'dismissable'      => true,
				'has_notices'      => true,
				'is_automatic'     => true,
				'strings'          => array(
					'page_title'                      => __( 'The7 Plugins', 'the7mk2' ),
					'menu_title'                      => __( 'The7 Plugins', 'the7mk2' ),
					'installing'                      => __( 'Installing Plugin: %s', 'the7mk2' ),
					'oops'                            => __( 'Something went wrong with the plugin API.', 'the7mk2' ),
					'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'the7mk2' ),
					'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'the7mk2' ),
					'notice_cannot_install'           => false,
					'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'the7mk2' ),
					'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'the7mk2' ),
					'notice_cannot_activate'          => false,
					'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'the7mk2' ),
					'notice_cannot_update'            => false,
					'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'the7mk2' ),
					'activate_link'                   => _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'the7mk2' ),
					'return'                          => __( 'Return to Plugins Installer', 'the7mk2' ),
					'plugin_activated'                => __( 'Plugin activated successfully.', 'the7mk2' ),
					'complete'                        => __( 'All plugins installed and activated successfully. %s', 'the7mk2' ),
					'nag_type'                        => 'updated',
				),
			) );

			global $tgmpa;
			if ( $tgmpa && ! $tgmpa->is_tgmpa_complete() ) {
				add_action( 'admin_print_footer_scripts', array( __CLASS__, 'print_inline_js_action' ) );
			}
		}

		public static function print_inline_js_action() {
			?>
			<script type="text/javascript">
				jQuery(function($) {
					$('#setting-error-tgmpa .notice-dismiss').unbind().on('click.the7.tgmpa.dismiss', function(event) {
						location.href = $('#setting-error-tgmpa a.dismiss-notice').attr('href');
					});
				});
			</script>
			<?php
		}

		/**
		 * Fires on the page load.
		 */
		public static function setup_hooks( $page_hook ) {
            add_action( 'load-' . $page_hook, array( __CLASS__, 'remove_update_filters' ) );
            add_action( 'load-' . $page_hook, array( __CLASS__, 'update_plugins_list_on_page_load' ) );
		}

		/**
		 * This function prevents plugin update api modification, so tgmpa can do its job.
		 */
		public static function remove_update_filters() {
		    $tgmpa_update = ( isset( $_GET['tgmpa-update'] ) ? $_GET['tgmpa-update'] : '' );

			if ( 'update-plugin' !== $tgmpa_update ) {
				return;
			}

			$tags_to_wipe = array(
				'pre_set_site_transient_update_plugins',
				'update_api',
				'upgrader_pre_download',
			);

			// Wipe out filters.
			foreach ( $tags_to_wipe as $tag ) {
				remove_all_filters( $tag );
			}
		}

		public static function update_plugins_list_on_page_load() {
			if ( time() - intval( get_site_option( 'the7_plugins_last_check', 0 ) ) > MINUTE_IN_SECONDS ) {
				self::get_update_plugin_list();
				update_site_option( 'the7_plugins_last_check', time() );
			}
        }

		/**
		 * Update plugins list.
		 *
		 * @uses The7_Remote_API
		 *
		 * @param $transient
		 *
		 * @return mixed
		 */
		public static function update_plugins_list( $transient ) {
            self::get_update_plugin_list();

  			return $transient;
		}

		public static function get_update_plugin_list() {
			$code = presscore_get_purchase_code();
			$the7_remote_api = new The7_Remote_API( $code );

			$plugins_list = $the7_remote_api->check_plugins_list();
			if ( $plugins_list && ! is_wp_error( $plugins_list ) ) {
                // Set plugins source.
                foreach ( $plugins_list as $index => $info ) {
                    if ( isset( $info['version'], $info['slug'] ) ) {
                        $plugins_list[ $index ]['source'] = $the7_remote_api->get_plugin_download_url( $info['slug'] );
                    }
                }

				$plugins_list = array_values( $plugins_list );

				// Store update info in db to use later in 'presscore_tgmpa_module_plugins_list' filter.
				self::set_plugins_list_cache( $plugins_list );
			}

			return $plugins_list;
        }

		/**
		 * Store plugins info.
		 *
		 * @param array $list
		 *
		 * @return bool
		 */
		public static function set_plugins_list_cache( $list = array() ) {
			return update_site_option( 'the7_plugins_list', $list );
		}

		/**
		 * Retrieve plugins info.
		 *
		 * @return array
		 */
		public static function get_plugins_list_cache() {
			return (array) get_site_option( 'the7_plugins_list', array() );
		}

		/**
		 * Delete plugins info.
		 *
		 * @return bool
		 */
		public static function delete_plugins_list_cache() {
			return delete_site_option( 'the7_plugins_list' );
		}
	}

	/**
	 * Important to override this function before TGM_Plugin_Activation class include!
     * This maneuver prevents original class from loading and allow us to extend it in subclass.
     */
	if ( ! function_exists( 'load_tgm_plugin_activation' ) ) {
		function load_tgm_plugin_activation() {
		    // Do nothing.
		}
	}

	Presscore_Modules_TGMPAModule::execute();

endif;
