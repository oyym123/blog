<?php
/**
 * TGMPA facade.
 *
 * @since   2.0.0
 * @package dt-dummy
 */

class The7_Demo_Content_TGMPA implements The7_Demo_Content_Plugins_Checker_Interface {

	/**
	 * array( 'slug' => 'name' )
	 * 
	 * @var array
	 */
	protected $inactive_plugins = array();

	/**
	 * @var array
	 */
	protected $plugins_to_install = array();

	/**
	 * Returns false if any of $plugins is not active, in other cases returns true.
	 * 
	 * @param  array   $plugins
	 * @return boolean
	 */
	public function is_plugins_active( $plugins = array() ) {
		global $tgmpa;

		$this->inactive_plugins = $this->plugins_to_install = array();

		if ( $plugins ) {
			foreach ( $plugins as $plugin_slug ) {
				if ( ! $tgmpa->is_plugin_installed( $plugin_slug ) ) {
					$this->plugins_to_install[ $plugin_slug ] = $this->get_plugin_name( $plugin_slug );
					continue;
				}

				if ( ! $tgmpa->is_plugin_active( $plugin_slug ) ) {
					$this->inactive_plugins[ $plugin_slug ] = $this->get_plugin_name( $plugin_slug );
				}
			}

			if ( $this->inactive_plugins || $this->plugins_to_install ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Returns array of inactive plugins.
	 *
	 * @return array
	 */
	public function get_inactive_plugins() {
		return $this->inactive_plugins;
	}

	public function get_plugins_to_install() {
		return $this->plugins_to_install;
	}

	/**
	 * If all plugins installed and active - returns empty string. In other cases returns url to tgmpa plugins page.
	 * 
	 * @return string
	 */
	public function get_install_plugins_page_link() {
		global $tgmpa;

		if ( $tgmpa->is_tgmpa_complete() ) {
			return '';
		}

		return $tgmpa->get_bulk_action_link();
	}

	/**
	 * Returns $slug plugin name if it is registered, in other cases returns $slug.
	 * 
	 * @param  string $slug
	 * @return string
	 */
	public function get_plugin_name( $slug ) {
		global $tgmpa;

		if ( isset( $tgmpa->plugins[ $slug ] ) ) {
			return $tgmpa->plugins[ $slug ]['name'];
		}

		return $slug;
	}

	/**
	 * Checks if $tgmpa global is not empty.
	 * 
	 * @return boolean
	 */
	public static function is_tgmpa_active() {
		return ( ! empty( $GLOBALS['tgmpa'] ) && is_a( $GLOBALS['tgmpa'], 'The7_TGMPA' ) );
	}
}
