<?php
/**
 * Dummy required plugins not installed view.
 *
 * @package dt-dummy
 * @since   2.0.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * @var The7_Demo_Content_Admin $this
 */

$plugins_info_text_1 = esc_html( __( 'In order to import this demo, you need to %s the following plugins:', 'the7mk2' ) );
$plugins_info_text_2 = esc_html( __( 'install', 'the7mk2' ) );

$inactive_plugins = $this->plugins_checker()->get_inactive_plugins();
$plugins_to_install = $this->plugins_checker()->get_plugins_to_install();

$tgmpa_link = $this->plugins_checker()->get_install_plugins_page_link();
if ( $tgmpa_link ) {
	$plugins_to_install = $this->plugins_checker()->get_plugins_to_install();

    $data = presscore_get_inlide_data_attr( array(
        'install-plugins' => esc_attr( implode( ',', array_keys( $plugins_to_install ) ) ),
        'activate-plugins' => esc_attr( implode( ',', array_keys( $inactive_plugins ) ) ),
    ) );

	$plugins_info_text_2 = '<a class="the7-demo-install-plugins" href="' . esc_url( $tgmpa_link ) . '" ' . $data . '>' . $plugins_info_text_2 . '</a>';
}
?>

	<div class="dt-dummy-controls-block">
        <div class="dt-dummy-required-plugins">
            <h4><?php printf( $plugins_info_text_1, $plugins_info_text_2 ); ?></h4>
            <p><?php echo implode( ', ', ($inactive_plugins + $plugins_to_install) ); ?></p>
        </div>
	</div>
