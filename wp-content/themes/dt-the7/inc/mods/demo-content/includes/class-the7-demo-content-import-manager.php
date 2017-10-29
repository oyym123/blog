<?php
/**
 * Manage all import behaviour.
 */

class The7_Demo_Content_Import_Manager {

	/**
	 * @var string
	 */
	public $content_dir;

	/**
	 * @var array
	 */
	protected $errors = array();

	/**
	 * @var The7_Demo_Content_Import
	 */
	protected $importer_obj;

	/**
	 * @var mixed
	 */
	protected $site_meta = null;

	/**
	 * DT_Dummy_Import_Manager constructor.
	 *
	 * @param string     $content_dir
	 */
	public function __construct( $content_dir ) {
		$this->content_dir = trailingslashit( $content_dir );
	}

	/**
	 * Downloads demo content package.
	 */
	public function download_dummy() {
		$item = basename( $this->content_dir );
		$code = presscore_get_purchase_code();
		$download_dir = dirname( $this->content_dir );
		$the7_remote_api = new The7_demo_Content_Remote_Server_API();
		$download_response = $the7_remote_api->download_dummy( $item, $code, $download_dir );

		if ( is_wp_error( $download_response ) ) {
			$error = ( the7_is_debug_on() ? $download_response->get_error_message() : sprintf( __( 'Import failed due to repository server error. Please try again in 30-60 minutes.
If the problem persists, please don\'t hesitate to contact our <a href="%s" target="_blank">support</a>.', 'the7mk2' ), 'http://support.dream-theme.com/' ) );
			$this->add_error($error);
			return false;
		}

		return trailingslashit( $download_response );
	}

	/**
	 * Remove temp dir.
	 */
	public function cleanup_temp_dir() {
		if ( ! $this->content_dir ) {
			return false;
		}

		$wp_uploads = wp_get_upload_dir();

		$dir_to_delete = dirname( $this->content_dir );
		if ( untrailingslashit( $wp_uploads['basedir'] ) === untrailingslashit( $dir_to_delete ) ) {
			return false;
		}

		if ( false === strpos( $dir_to_delete, $wp_uploads['basedir'] ) ) {
			return false;
		}

		global $wp_filesystem;

		if ( ! $wp_filesystem && ! WP_Filesystem() ) {
			return false;
		}

		$wp_filesystem->delete( $dir_to_delete, true );

		return true;
	}

	/**
	 * Import post types dummy.
	 */
	public function import_post_types() {
		$import_options = array(
			'fetch_attachments' => false,
		);

		ob_start();
		$this->rename_existing_menus();
		$this->import_file( $this->content_dir . 'full-content.xml', $import_options );
		ob_end_clean();
	}

	public function import_attachments( $include_attachments = false ) {
		$import_options = array(
			'include_attachments' => $include_attachments,
			'fetch_attachments' => true,
		);

		add_filter( 'wp_import_post_data_raw', array( $this, 'import_only_attachments' ) );

		ob_start();
		$this->import_file( $this->content_dir . 'full-content.xml', $import_options );
		ob_end_clean();
	}

	/**
	 * Rename existing menus.
	 */
	public function rename_existing_menus() {
		$menus = wp_get_nav_menus();

		if ( ! empty( $menus ) ) {
			foreach ( $menus as $menu ) {
				$updated = false;
				$i       = 0;

				while ( ! is_numeric( $updated ) ) {
					$i ++;
					$args['menu-name']   = __( 'Previously used menu', 'the7mk2' ) . " " . $i;
					$args['description'] = $menu->description;
					$args['parent']      = $menu->parent;

					$updated = wp_update_nav_menu_object( $menu->term_id, $args );

					if ( $i > 100 ) {
						$updated = 1;
					}
				}
			}
		}
	}

	private function importer_bootstrap() {
		if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
			define( 'WP_LOAD_IMPORTERS', true );
		}

		// Load Importer API.
		require_once ABSPATH . 'wp-admin/includes/import.php';

		// Load WP_Importer.
		if ( ! class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			if ( ! file_exists( $class_wp_importer ) ) {
				return false;
			}

			require_once $class_wp_importer;
		}

		// Load WP_Import.
		if ( ! class_exists( 'WP_Import' ) ) {
			$class_wp_import = the7_demo_content_dir_path() . 'includes/wordpress-importer/wordpress-importer.php';
			if ( ! file_exists( $class_wp_import ) ) {
				return false;
			}

			require_once $class_wp_import;
		}

		// Load custom importer.
		if ( ! class_exists( 'The7_Demo_Content_Import', false ) ) {
			require the7_demo_content_dir_path() . 'includes/class-the7-demo-content-import.php';
		}

		return true;
	}

	private function import_file( $file_name, $options = array() ) {
		$default_options = array(
			'include_attachments' => false,
			'fetch_attachments' => true,
		);
		$options = wp_parse_args( $options, $default_options );

		if ( ! is_file( $file_name ) ) {
			$this->add_error( __( "The XML file containing the dummy content is not available or could not be read in <pre>{$file_name}</pre>", 'the7mk2' ) );
			return false;
		}

		if ( ! $this->importer_bootstrap() ) {
			$this->add_error( __( 'The Auto importing script could not be loaded.', 'the7mk2' ) );
			return false;
		}

		if ( ! $options['include_attachments'] ) {
			add_filter( 'wp_import_post_data_raw', array( $this, 'replace_attachment_url' ) );
		}

		$this->do_wc_compatibility_actions( $file_name );

		$this->importer_obj = new The7_Demo_Content_Import();
		$this->importer_obj->fetch_attachments = $options['fetch_attachments'];
		$this->importer_obj->import( $file_name );

		return true;
	}

	/**
	 * @param $file
	 */
	public function do_wc_compatibility_actions( $file ) {
		global $wpdb;

		if ( ! the7_demo_content_wc_is_active() || ! class_exists( 'WXR_Parser' ) )
			return;

		/**
		 * Fix Fatal Error while process orphaned variations.
		 */
		remove_filter( 'post_type_link', array( 'WC_Post_Data', 'variation_post_link' ) );
		add_filter( 'post_type_link', array( $this, 'variation_post_link' ), 10, 2 );

		$parser = new WXR_Parser();
		$import_data = $parser->parse( $file );

		if ( isset( $import_data['posts'] ) ) {
			$posts = $import_data['posts'];

			if ( $posts && sizeof( $posts ) > 0 ) foreach ( $posts as $post ) {

				if ( $post['post_type'] == 'product' ) {

					if ( $post['terms'] && sizeof( $post['terms'] ) > 0 ) {

						foreach ( $post['terms'] as $term ) {

							$domain = $term['domain'];

							if ( strstr( $domain, 'pa_' ) ) {

								// Make sure it exists!
								if ( ! taxonomy_exists( $domain ) ) {

									$nicename = strtolower( sanitize_title( str_replace( 'pa_', '', $domain ) ) );

									$exists_in_db = $wpdb->get_var( $wpdb->prepare( "SELECT attribute_id FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = %s;", $nicename ) );

									// Create the taxonomy
									if ( ! $exists_in_db )
										$wpdb->insert( $wpdb->prefix . "woocommerce_attribute_taxonomies", array( 'attribute_name' => $nicename, 'attribute_type' => 'select', 'attribute_orderby' => 'menu_order' ), array( '%s', '%s', '%s' ) );

									// Register the taxonomy now so that the import works!
									register_taxonomy( $domain,
										apply_filters( 'woocommerce_taxonomy_objects_' . $domain, array('product') ),
										apply_filters( 'woocommerce_taxonomy_args_' . $domain, array(
											'hierarchical' => true,
											'show_ui' => false,
											'query_var' => true,
											'rewrite' => false,
										) )
									);
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Link to parent products when getting permalink for variation. Fail safe.
	 *
	 * @see WC_Post_Data::variation_post_link()
	 *
	 * @param $permalink
	 * @param $post
	 *
	 * @return string
	 */
	public function variation_post_link( $permalink, $post ) {
		if ( 'product_variation' === $post->post_type && function_exists( 'wc_get_product' ) ) {
			$variation = wc_get_product( $post->ID );
			if ( is_object( $variation ) ) {
				return $variation->get_permalink();
			}
		}
		return $permalink;
	}

	/**
	 * Import theme options.
	 */
	public function import_theme_option() {
		$site_meta = $this->get_site_meta();

		if ( isset( $site_meta['theme_options'] ) ) {
			$known_options = get_option( 'optionsframework' );
			if ( isset( $known_options['id'] ) ) {
				$theme_options = $this->filter_theme_options( $site_meta['theme_options'] );

				update_option( $known_options['id'], $theme_options );

				$this->empty_theme_cache();
			}

			// Import widgets settings.
			if ( ! empty( $site_meta['widgets_settings'] ) && is_array( $site_meta['widgets_settings'] ) ) {
				foreach( $site_meta['widgets_settings'] as $key => $setting) {
					update_option( $key, $setting );
				}
			}
		}
	}

	/**
	 * Import wp settings.
	 */
	public function import_wp_settings() {
		$site_meta = $this->get_site_meta();

		// Import wp settings.
		if ( isset( $site_meta['wp_settings'] ) ) {
			$wp_settings = wp_parse_args( $site_meta['wp_settings'], array(
				'show_on_front' => false,
				'page_on_front' => false,
				'page_for_posts' => false,
			) );

			if ( 'page' === $wp_settings['show_on_front'] ) {
				$page_on_front = $this->importer_get_processed_post( $wp_settings['page_on_front'] );
				if ( 'page' == get_post_type( $page_on_front ) ) {
					update_option( 'show_on_front', 'page' );
					update_option( 'page_on_front', $page_on_front );
				}

				$page_for_posts = $this->importer_get_processed_post( $wp_settings['page_for_posts'] );
				if ( 'page' == get_post_type( $page_for_posts ) ) {
					update_option( 'page_for_posts', $page_for_posts );
				}
			}
		}

		// Import menus.
		if ( isset( $site_meta['nav_menu_locations'] ) ) {
			$this->set_menus( $site_meta['nav_menu_locations'] );
		}
	}

	/**
	 * Import ultimate addons settings.
	 */
	public function import_ultimate_addons_settings() {
		// Code not finished, don't use.
		return;

		$site_meta = $this->get_site_meta();

		// Import Ultimate Addons Selected Fonts.
		if ( ! empty( $site_meta['ultimate_selected_google_fonts'] ) && is_array( $site_meta['ultimate_selected_google_fonts'] ) ) {
			$demo_fonts = $site_meta['ultimate_selected_google_fonts'];
			$site_fonts = get_option( 'ultimate_selected_google_fonts', array() );
			if ( empty( $site_fonts ) ) {
				update_option( 'ultimate_selected_google_fonts', $demo_fonts );
			} else {
				$site_fonts_index = wp_list_pluck( $site_fonts, 'font_family' );
				foreach ( $demo_fonts as $google_font ) {
					$site_font_index = array_search( $google_font['font_family'], $site_fonts_index );
					if ( false === $site_font_index ) {
						$site_fonts[] = $google_font;
					} else {
						$site_fonts[ $site_font_index ]['variants'] = '';
					}
				}

				update_option( 'ultimate_selected_google_fonts', $site_fonts );
			}
		}
	}

	/**
	 * Import revoluton slider sliders.
	 */
	public function import_rev_sliders() {
		$site_meta = $this->get_site_meta();

		if ( empty( $site_meta['revolution_sliders'] ) ) {
			return;
		}

		include_once 'class-the7-demo-content-revslider-importer.php';
		$rev_slider_importer = new The7_Demo_Content_Revslider_Importer();

		$rev_sliders = (array) $site_meta['revolution_sliders'];
		foreach ( $rev_sliders as $rev_slider ) {
			$rev_slider_importer->import_slider( $this->content_dir . "{$rev_slider}.zip" );
		}
	}

	/**
	 * Return site meta - decoded site-meta.json file content.
	 *
	 * @return mixed
	 */
	public function get_site_meta() {
		if ( is_null( $this->site_meta ) ) {
			$this->site_meta = json_decode( file_get_contents( $this->content_dir . 'site-meta.json' ), true );
		}

		return $this->site_meta;
	}

	/**
	 * Filter theme options. Replace logos with default one if needed.
	 *
	 * @param $theme_options
	 *
	 * @return mixed
	 */
	protected function filter_theme_options( $theme_options ) {
		// Big logo.
		$theme_options = $this->theme_options_replace_logo(
			$theme_options,
			array(
				'header-logo',
				'header-style-transparent-logo',
			),
			array(
				'regular' => array(
					'/images/logo-main-dummy.png?w=80&h=80',
					0,
				),
				'hd' => array(
					'/images/logo-main-dummy-hd.png?w=160&h=160',
					0,
				),
			)
		);

		// Small logo.
		$theme_options = $this->theme_options_replace_logo(
			$theme_options,
			array(
				'header-style-mixed-logo',
				'header-style-floating-logo',
				'header-style-mobile-logo',
				'bottom_bar-logo',
			),
			array(
				'regular' => array(
					'/images/logo-small-dummy.png?w=60&h=60',
					0,
				),
				'hd' => array(
					'/images/logo-small-dummy-hd.png?w=120&h=120',
					0,
				),
			)
		);

		// Silence plugins notification by default.
		$theme_options['general-hide_plugins_notifications'] = '1';

		return $theme_options;
	}

	protected function theme_options_replace_logo( $options, $fields_ids, $logo ) {
		foreach ( $fields_ids as $base_id ) {
			// Logo prefix 'regular', 'hd'.
			foreach ( $logo as $prefix => $val ) {
				$field_id = "{$base_id}_{$prefix}";
				// If not empty - replace.
				if ( isset( $options[ $field_id ] ) && ( ! empty( $options[ $field_id ][0] ) || ! empty( $options[ $field_id ][1] ) ) ) {
					$options[ $field_id ] = $val;
				}
			}
		}

		return $options;
	}

	/**
	 * Empty theme css cache.
	 */
	private function empty_theme_cache() {
		if ( function_exists( 'presscore_set_force_regenerate_css' ) ) {
			presscore_set_force_regenerate_css( true );
		}

		if ( function_exists( 'presscore_cache_loader_inline_css' ) ) {
			presscore_cache_loader_inline_css( '' );
		}
	}

	/**
	 * Returns imported post new id or false.
	 *
	 * @param $post_id
	 *
	 * @return bool
	 */
	private function importer_get_processed_post( $post_id ) {
		if ( $this->importer_obj && isset( $this->importer_obj->processed_posts[ $post_id ] ) ) {
			return $this->importer_obj->processed_posts[ $post_id ];
		}

		return false;
	}

	/**
	 * Set nav menu locations.
	 *
	 * @param $nav_menu_locations
	 */
	protected function set_menus( $nav_menu_locations ) {
		$locations = get_theme_mod( 'nav_menu_locations' );

		foreach ( $nav_menu_locations as $location => $menu_id ) {
			$locations[ $location ] = $this->importer_get_processed_terms( $menu_id );
		}

		set_theme_mod( 'nav_menu_locations', $locations );
	}

	/**
	 * Returns imported term new id or false.
	 *
	 * @param $term_id
	 *
	 * @return bool
	 */
	private function importer_get_processed_terms( $term_id ) {
		if ( $this->importer_obj && isset( $this->importer_obj->processed_terms[ $term_id ] ) ) {
			return $this->importer_obj->processed_terms[ $term_id ];
		}

		return false;
	}

	/**
	 * Add error.
	 *
	 * @param string $msg
	 */
	public function add_error( $msg ) {
		$this->errors[] = $msg;
	}

	/**
	 * Returns errors string.
	 *
	 * @return string
	 */
	public function get_errors_string() {
		return implode( '', $this->errors );
	}

	/**
	 * @return bool
	 */
	public function has_errors() {
		return ( ! empty( $this->errors ) );
	}

	/**
	 * Replace attachments with noimage dummies.
	 *
	 * @param $raw_post
	 *
	 * @return mixed
	 */
	public function replace_attachment_url( $raw_post ) {
		if ( isset( $raw_post['post_type'] ) && 'attachment' == $raw_post['post_type'] ) {
			$raw_post['attachment_url'] = $raw_post['guid'] = $this->get_noimage_url( $raw_post['attachment_url'] );
		}

		return $raw_post;
	}

	/**
	 * @param $raw_post
	 *
	 * @return mixed
	 */
	public function import_only_attachments( $raw_post ) {
		if ( 'attachment' != $raw_post['post_type'] ) {
			$raw_post['status'] = 'auto-draft';
		}

		return $raw_post;
	}

	/**
	 * Returns dummy image src.
	 *
	 * @param string $origin_img_url
	 *
	 * @return string
	 */
	private function get_noimage_url( $origin_img_url ) {
		switch ( pathinfo( $origin_img_url, PATHINFO_EXTENSION ) ) {
			case 'jpg':
			case 'jpeg':
				$ext = 'jpg';
				break;

			case 'png':
				$ext = 'png';
				break;

			case 'gif':
			default:
				$ext = 'gif';
				break;
		}
		$noimage_fname = 'noimage.' . $ext;

		return the7_demo_content_dir_url( 'admin/images/' . $noimage_fname );
	}
}
