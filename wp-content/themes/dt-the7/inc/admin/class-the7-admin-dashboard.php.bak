<?php

class The7_Admin_Dashboard {

	/**
	 * @var array
	 */
	protected $pages = array();

	/**
	 * The7_Admin_Dashboard constructor.
	 */
	public function __construct() {
		$this->pages = array(
			'the7-dashboard'    => array(
				'title'      => __( 'Dashboard', 'the7mk2' ),
				'capability' => 'edit_theme_options',
			),
			'the7-demo-content' => array(
				'title'      => __( 'Pre-made Websites', 'the7mk2' ),
				'capability' => 'edit_theme_options',
			),
			'the7-plugins'      => array(
				'title'      => __( 'Plugins', 'the7mk2' ),
				'capability' => 'install_plugins',
			),
			'the7-status'       => array(
				'title'      => __( 'Service Information', 'the7mk2' ),
				'capability' => 'edit_theme_options',
			),
		);
	}

	/**
	 * Init admin dashboard. Add hooks and all the needed to dashboard works.
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		add_action( 'after_switch_theme', array( $this, 'redirect_to_dashboard' ) );
	}

	/**
	 * Add admin pages.
	 */
	public function add_menu_page() {
		$dashboard_slug = $this->get_main_page_slug();
		$dashboard = $this->get_main_page();

		$the7_page = add_menu_page(
			$dashboard['title'],
			__( 'The7', 'the7mk2' ),
			$dashboard['capability'],
			$dashboard_slug,
			array( $this, 'menu_page_screen' ),
            '',
            3
		);

		add_action( 'admin_print_styles-' . $the7_page, array( $this, 'enqueue_dashboard_styles' ) );
		add_action( 'admin_print_styles-' . $the7_page, array( $this, 'enqueue_styles' ) );
		add_action( 'admin_print_scripts-' . $the7_page, array( $this, 'enqueue_scripts' ) );

		$sub_page_hook_suffix = array();
        $sub_pages = $this->get_sub_pages();

		foreach ( $sub_pages as $sub_page_slug=>$sub_page ) {
			$hook_suffix = add_submenu_page(
				$dashboard_slug,
				$sub_page['title'],
				$sub_page['title'],
				$sub_page['capability'],
				$sub_page_slug,
				array( $this, 'menu_page_screen' )
			);
            $sub_page_hook_suffix[ $sub_page_slug ] = $hook_suffix;

			// Adds actions to hook in the required css and javascript
            add_action( 'admin_print_styles-' . $hook_suffix, array( $this, 'enqueue_styles' ) );
            add_action( 'admin_print_scripts-' . $hook_suffix, array( $this, 'enqueue_scripts' ) );
		}

		// Additional actions:

        // Demo content.
		add_action( 'load-' . $sub_page_hook_suffix['the7-demo-content'], array( the7_demo_content()->remote, 'update_check' ) );
		add_action( 'admin_print_styles-' . $sub_page_hook_suffix['the7-demo-content'], array( the7_demo_content()->admin, 'enqueue_styles' ) );
		add_action( 'admin_print_scripts-' . $sub_page_hook_suffix['the7-demo-content'], array( the7_demo_content()->admin, 'enqueue_scripts' ) );

		// Plugins.
		Presscore_Modules_TGMPAModule::setup_hooks( $sub_page_hook_suffix['the7-plugins'] );

		// Theme registration.
		Presscore_Modules_ThemeUpdateModule::setup_hooks( $the7_page );

		global $submenu;
		if ( isset( $submenu[ $dashboard_slug ] ) ) {
			$submenu[ $dashboard_slug ][0][0] = $dashboard['title'];
		}
	}

	/**
	 * This method choose which screen to show.
	 */
	public function menu_page_screen() {
		global $plugin_page;

        $view_file = PRESSCORE_ADMIN_DIR . '/screens/' . basename( $plugin_page ) . '.php';
        if ( is_readable( $view_file ) ) {
            include $view_file;
        }
	}

	/**
	 * Enqueue common styles.
	 */
	public function enqueue_styles() {
        wp_enqueue_style( 'the7-dashboard', PRESSCORE_ADMIN_URI . '/assets/the7-dashboard.css', array(), wp_get_theme()->get( 'Version' ) );
    }

	/**
	 * Enqueue common scripts.
	 */
    public function enqueue_scripts() {
	    wp_enqueue_script( 'the7-dashboard', PRESSCORE_ADMIN_URI . '/assets/the7-dashboard.js', array(), wp_get_theme()->get( 'Version' ) );
    }

	/**
	 * Enqueue styles for dashboard page.
	 */
    public function enqueue_dashboard_styles() {
    	wp_enqueue_style( 'the7-dashboard-icons', PRESSCORE_ADMIN_URI . '/assets/dashboard-icons.css', array(), wp_get_theme()->get( 'Version' ) );
    }

	/**
	 * Redirect to theme dashboard.
	 */
	public function redirect_to_dashboard() {
	    $main_page_slug = $this->get_main_page_slug();
        wp_safe_redirect( admin_url( "admin.php?page=$main_page_slug" ) );
    }

	/**
     * Return dashboard main page slug.
     *
	 * @return string
	 */
    protected function get_main_page_slug() {
	    reset( $this->pages );

	    return key( $this->pages );
    }

	/**
     * Return dashboard main page title.
     *
	 * @return string
	 */
    protected function get_main_page() {
	    reset( $this->pages );

	    return current( $this->pages );
    }

	/**
     * Return dashboard sub pages as array( 'slug' => 'title' ).
     *
	 * @return array
	 */
    protected function get_sub_pages() {
	    $pages = $this->pages;
	    array_shift( $pages );

	    return $pages;
    }
}