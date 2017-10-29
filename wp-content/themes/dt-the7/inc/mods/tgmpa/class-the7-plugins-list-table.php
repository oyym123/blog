<?php

if ( ! class_exists( 'The7_Plugins_List_Table' ) ) {

	/**
	 * List table class for handling plugins.
	 */
	class The7_Plugins_List_Table extends TGMPA_List_Table {

		/**
		 * Categorize the plugins which have open actions into views for the TGMPA page.
		 */
		protected function categorize_plugins_to_views() {
			$plugins = array(
				'all'      => array(), // Meaning: all plugins which still have open actions.
				'install'  => array(),
				'update'   => array(),
				'activate' => array(),
			);

			// Show all plugins.
			foreach ( $this->tgmpa->plugins as $slug => $plugin ) {
				$plugins['all'][ $slug ] = $plugin;

				if ( ! $this->tgmpa->is_plugin_installed( $slug ) ) {
					$plugins['install'][ $slug ] = $plugin;
				} else {
					if ( false !== $this->tgmpa->does_plugin_have_update( $slug ) ) {
						$plugins['update'][ $slug ] = $plugin;
					}

					if ( $this->tgmpa->can_plugin_activate( $slug ) ) {
						$plugins['activate'][ $slug ] = $plugin;
					}
				}
			}

			return $plugins;
		}

		/**
		 * Determine the plugin status message.
		 *
		 * @param string $slug Plugin slug.
		 * @return string
		 */
		protected function get_plugin_status_text( $slug ) {
			if ( ! $this->tgmpa->is_plugin_installed( $slug ) ) {
				return __( 'Not Installed', 'tgmpa' );
			}

			if ( ! $this->tgmpa->is_plugin_active( $slug ) ) {
				$install_status = __( 'Installed But Not Activated', 'tgmpa' );
			} else {
				$install_status = __( 'Active', 'tgmpa' );
			}

			if ( $this->tgmpa->does_plugin_require_update( $slug ) && false === $this->tgmpa->does_plugin_have_update( $slug ) ) {
				$update_status = __( 'Required Update not Available', 'tgmpa' );

			} elseif ( $this->tgmpa->does_plugin_require_update( $slug ) ) {
				$update_status = __( 'Requires Update', 'tgmpa' );

			} elseif ( false !== $this->tgmpa->does_plugin_have_update( $slug ) ) {
				$update_status = __( 'Update recommended', 'tgmpa' );
			} else {
				$update_status = __( 'Up to date', 'tgmpa' );
			}

			return sprintf(
			/* translators: 1: install status, 2: update status */
				_x( '%1$s, %2$s', 'Install/Update Status', 'tgmpa' ),
				$install_status,
				$update_status
			);
		}

		/**
		 * Get the plugin source type text string.
		 *
		 * @since 2.5.0
		 *
		 * @param string $type Plugin type.
		 * @return string
		 */
		protected function get_plugin_source_type_text( $type ) {
			$string = '';

			switch ( $type ) {
				case 'repo':
					$string = __( 'WordPress Repository', 'tgmpa' );
					break;
				case 'external':
					if ( presscore_theme_is_activated() ) {
						$string = __( 'External Source', 'tgmpa' );
					} else {
						$string = wp_kses( sprintf( __( 'Please <a href="%s">register</a> the theme', 'tgmpa' ), admin_url( 'admin.php?page=the7-dashboard' ) ), array( 'a' => array( 'href' => true ) ) );
					}
					break;
				case 'bundled':
					$string = __( 'Pre-Packaged', 'tgmpa' );
					break;
			}

			return $string;
		}

		/**
		 * Get the actions which are relevant for a specific plugin row.
		 *
		 * @param array $item Array of item data.
		 * @return array Array with relevant action links.
		 */
		protected function get_row_actions( $item ) {
			$item_source = 'external';
			$item_slug = $item['slug'];
			if ( array_key_exists( $item_slug, $this->tgmpa->plugins ) && isset( $this->tgmpa->plugins[ $item_slug ]['source'] ) ) {
				$item_source = $this->tgmpa->plugins[ $item_slug ]['source'];
			}

			// If it's an external plugin and theme is not registered - show no actions
			if ( 'repo' !== $item_source && ! presscore_theme_is_activated() ) {
				$prefix = ( defined( 'WP_NETWORK_ADMIN' ) && WP_NETWORK_ADMIN ) ? 'network_admin_' : '';
				return apply_filters( "tgmpa_{$prefix}plugin_action_links", array(), $item['slug'], $item, $this->view_context );
			}

			$actions      = array();
			$action_links = array();

			// Display the 'Install' action link if the plugin is not yet available.
			if ( ! $this->tgmpa->is_plugin_installed( $item['slug'] ) ) {
				/* translators: %2$s: plugin name in screen reader markup */
				$actions['install'] = __( 'Install %2$s', 'tgmpa' );
			} else {
				// Display the 'Update' action link if an update is available and WP complies with plugin minimum.
				if ( false !== $this->tgmpa->does_plugin_have_update( $item['slug'] ) && $this->tgmpa->can_plugin_update( $item['slug'] ) ) {
					/* translators: %2$s: plugin name in screen reader markup */
					$actions['update'] = __( 'Update %2$s', 'tgmpa' );
				}

				// Display the 'Activate' action link, but only if the plugin meets the minimum version.
				if ( $this->tgmpa->can_plugin_activate( $item['slug'] ) ) {
					/* translators: %2$s: plugin name in screen reader markup */
					$actions['activate'] = __( 'Activate %2$s', 'tgmpa' );
				}
			}

			// Create the actual links.
			foreach ( $actions as $action => $text ) {
				$nonce_url = wp_nonce_url(
					add_query_arg(
						array(
							'plugin'           => urlencode( $item['slug'] ),
							'tgmpa-' . $action => $action . '-plugin',
						),
						$this->tgmpa->get_tgmpa_url()
					),
					'tgmpa-' . $action,
					'tgmpa-nonce'
				);

				$action_links[ $action ] = sprintf(
					'<a href="%1$s">' . esc_html( $text ) . '</a>', // $text contains the second placeholder.
					esc_url( $nonce_url ),
					'<span class="screen-reader-text">' . esc_html( $item['sanitized_plugin'] ) . '</span>'
				);
			}

			$prefix = ( defined( 'WP_NETWORK_ADMIN' ) && WP_NETWORK_ADMIN ) ? 'network_admin_' : '';
			return apply_filters( "tgmpa_{$prefix}plugin_action_links", array_filter( $action_links ), $item['slug'], $item, $this->view_context );
		}
	}
}