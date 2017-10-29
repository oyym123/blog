<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="the7-dashboard" class="wrap the7-status">
    <h1><?php esc_html_e( 'Service Information', 'the7mk2' ); ?></h1>
    <div class="the7-column-container">

        <div class="the7-column the7-column-double">

        <table class="widefat" cellspacing="0">
            <thead>
            <tr>
                <th colspan="3" data-export-label="WordPress Environment"><?php _e( 'WordPress Environment', 'the7mk2' ); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td data-export-label="Home URL"><?php _e( 'Home URL:', 'the7mk2' ); ?></td>
                <td><?php echo home_url(); ?></td>
            </tr>
            <tr>
                <td data-export-label="Site URL"><?php _e( 'Site URL:', 'the7mk2' ); ?></td>
                <td><?php echo site_url(); ?></td>
            </tr>
            <tr>
                <td data-export-label="WP Version"><?php _e( 'WP Version:', 'the7mk2' ); ?></td>
                <td><?php bloginfo( 'version' ); ?></td>
            </tr>
            <tr>
                <td data-export-label="WP Multisite"><?php _e( 'WP Multisite:', 'the7mk2' ); ?></td>
                <td><?php echo ( is_multisite() ) ? '&#10004;' : '&ndash;'; ?></td>
            </tr>
            <tr>
                <td data-export-label="WP Memory Limit"><?php _e( 'WP Memory Limit:', 'the7mk2' ); ?></td>
                <td>
                    <?php echo size_format( presscore_get_wp_memory_limit() ); ?>
                </td>
            </tr>
            <tr>
                <td data-export-label="WP Debug Mode"><?php _e( 'WP Debug Mode:', 'the7mk2' ); ?></td>
                <td>
                    <?php if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) : ?>
                        <span class="yes">&#10004;</span>
                    <?php else : ?>
                        <span class="no">&ndash;</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td data-export-label="Language"><?php _e( 'Language:', 'the7mk2' ); ?></td>
                <td><?php echo get_locale() ?></td>
            </tr>
            </tbody>
        </table>

        <table class="widefat" cellspacing="0">
            <thead>
            <tr>
                <th colspan="3" data-export-label="Server Environment"><?php _e( 'Server Environment', 'the7mk2' ); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td data-export-label="Server Info"><?php _e( 'Server Info:', 'the7mk2' ); ?></td>
                <td><?php echo esc_html( $_SERVER['SERVER_SOFTWARE'] ); ?></td>
            </tr>
            <tr>
                <td data-export-label="PHP Version"><?php _e( 'PHP Version:', 'the7mk2' ); ?></td>
                <td><?php if ( function_exists( 'phpversion' ) ) { echo esc_html( phpversion() ); } ?></td>
            </tr>
            <?php if ( function_exists( 'ini_get' ) ) : ?>
                <tr>
                    <td data-export-label="PHP Post Max Size"><?php _e( 'PHP Post Max Size:', 'the7mk2' ); ?></td>
                    <td><?php echo size_format( wp_convert_hr_to_bytes( ini_get( 'post_max_size' ) ) ); ?></td>
                </tr>
                <tr>
                    <td data-export-label="PHP Time Limit"><?php _e( 'PHP Time Limit:', 'the7mk2' ); ?></td>
                    <td>
                        <?php
                        $time_limit = ini_get( 'max_execution_time' );
                        echo $time_limit;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td data-export-label="PHP Max Input Vars"><?php _e( 'PHP Max Input Vars:', 'the7mk2' ); ?></td>
                    <?php
                    $registered_navs = get_nav_menu_locations();
                    $menu_items_count = array( '0' => '0' );
                    foreach ( $registered_navs as $handle => $registered_nav ) {
                        $menu = wp_get_nav_menu_object( $registered_nav );
                        if ( $menu ) {
                            $menu_items_count[] = $menu->count;
                        }
                    }

                    $max_items = max( $menu_items_count );
                    $required_input_vars = $max_items * 20;
                    ?>
                    <td>
                        <?php
                        $max_input_vars = ini_get( 'max_input_vars' );
                        $required_input_vars = $required_input_vars + ( 500 + 1000 );

                        echo $max_input_vars;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td data-export-label="SUHOSIN Installed"><?php _e( 'SUHOSIN Installed:', 'the7mk2' ); ?></td>
                    <td><?php echo extension_loaded( 'suhosin' ) ? '&#10004;' : '&ndash;'; ?></td>
                </tr>
                <?php if ( extension_loaded( 'suhosin' ) ) :  ?>
                    <tr>
                        <td data-export-label="Suhosin Post Max Vars"><?php _e( 'Suhosin Post Max Vars:', 'the7mk2' ); ?></td>
                        <?php
                        $registered_navs = get_nav_menu_locations();
                        $menu_items_count = array( '0' => '0' );
                        foreach ( $registered_navs as $handle => $registered_nav ) {
                            $menu = wp_get_nav_menu_object( $registered_nav );
                            if ( $menu ) {
                                $menu_items_count[] = $menu->count;
                            }
                        }

                        $max_items = max( $menu_items_count );
                        $required_input_vars = $max_items * 20;
                        ?>
                        <td>
                            <?php
                            $max_input_vars = ini_get( 'suhosin.post.max_vars' );
                            $required_input_vars = $required_input_vars + ( 500 + 1000 );

                            echo $max_input_vars;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td data-export-label="Suhosin Request Max Vars"><?php _e( 'Suhosin Request Max Vars:', 'the7mk2' ); ?></td>
                        <?php
                        $registered_navs = get_nav_menu_locations();
                        $menu_items_count = array( '0' => '0' );
                        foreach ( $registered_navs as $handle => $registered_nav ) {
                            $menu = wp_get_nav_menu_object( $registered_nav );
                            if ( $menu ) {
                                $menu_items_count[] = $menu->count;
                            }
                        }

                        $max_items = max( $menu_items_count );
                        $required_input_vars = $max_items * 20;
                        ?>
                        <td>
                            <?php
                            $max_input_vars = ini_get( 'suhosin.request.max_vars' );
                            $required_input_vars = $required_input_vars + ( 500 + 1000 );
                            echo $max_input_vars;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td data-export-label="Suhosin Post Max Value Length"><?php _e( 'Suhosin Post Max Value Length:', 'the7mk2' ); ?></td>
                        <td><?php
                            $suhosin_max_value_length = ini_get( 'suhosin.post.max_value_length' );
                            $recommended_max_value_length = 2000000;
                            echo $suhosin_max_value_length;
                            ?></td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?>
            <tr>
                <td data-export-label="ZipArchive"><?php _e( 'ZipArchive:', 'the7mk2' ); ?></td>
                <td><?php echo class_exists( 'ZipArchive' ) ? '<span class="yes">&#10004;</span>' : '<span class="error">No.</span>'; ?></td>
            </tr>
            <tr>
                <td data-export-label="MySQL Version"><?php _e( 'MySQL Version:', 'the7mk2' ); ?></td>
                <td>
                    <?php global $wpdb; ?>
                    <?php echo $wpdb->db_version(); ?>
                </td>
            </tr>
            <tr>
                <td data-export-label="Max Upload Size"><?php _e( 'Max Upload Size:', 'the7mk2' ); ?></td>
                <td><?php echo size_format( wp_max_upload_size() ); ?></td>
            </tr>
            <tr>
                <td data-export-label="GD Library"><?php _e( 'GD Library:', 'the7mk2' ); ?></td>
                <td>
                    <?php
                    $info = esc_attr__( 'Not Installed', 'the7mk2' );
                    if ( extension_loaded( 'gd' ) && function_exists( 'gd_info' ) ) {
                        $info = esc_attr__( 'Installed', 'the7mk2' );
                        $gd_info = gd_info();
                        if ( isset( $gd_info['GD Version'] ) ) {
                            $info = $gd_info['GD Version'];
                        }
                    }
                    echo $info;
                    ?>
                </td>
            </tr>
            </tbody>
        </table>
        
        </div>

        <div class="the7-column the7-column-double">

        <table class="widefat" cellspacing="0">
            <thead>
            <tr>
                <th colspan="3" data-export-label="The7 Versions"><?php _e( 'The7 Versions', 'the7mk2' ); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td data-export-label="Current Version"><?php _e( 'Current Version:', 'the7mk2' ); ?></td>
                <td><?php echo wp_get_theme()->get( 'Version' ); ?></td>
            </tr>
            </tbody>
        </table>

        <table class="widefat" cellspacing="0" id="status">
            <thead>
            <tr>
                <th colspan="3" data-export-label="Active Plugins (<?php echo count( (array) get_option( 'active_plugins' ) ); ?>)"><?php _e( 'Active Plugins', 'the7mk2' ); ?> (<?php echo count( (array) get_option( 'active_plugins' ) ); ?>)</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $active_plugins = (array) get_option( 'active_plugins', array() );

            if ( is_multisite() ) {
                $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
            }

            foreach ( $active_plugins as $plugin ) {

                $plugin_data    = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
                $dirname        = dirname( $plugin );
                $version_string = '';
                $network_string = '';

                if ( ! empty( $plugin_data['Name'] ) ) {

                    // Link the plugin name to the plugin url if available.
                    $plugin_name = esc_html( $plugin_data['Name'] );

                    if ( ! empty( $plugin_data['PluginURI'] ) ) {
                        $plugin_name = '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . __( 'Visit plugin homepage' , 'the7mk2' ) . '">' . $plugin_name . '</a>';
                    }
                    ?>
                    <tr>
                        <td><?php echo $plugin_name; ?></td>
                        <td><?php printf( _x( 'by %s', 'by author', 'the7mk2' ), $plugin_data['Author'] ) . ' &ndash; ' . esc_html( $plugin_data['Version'] ) . $version_string . $network_string; ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>

        </div>

    </div>
</div>