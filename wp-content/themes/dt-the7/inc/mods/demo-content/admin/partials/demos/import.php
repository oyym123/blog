<?php
/**
 * Dummy info view.
 *
 * @package dt-dummy
 * @since   2.0.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>

<?php if ( ! empty( $dummy_info['top_content'] ) ) : ?>

	<div class="dt-dummy-controls-block dt-dummy-info-content">
		<?php echo wp_kses_post( $dummy_info['top_content'] ); ?>
	</div>

<?php endif; ?>

<?php if ( empty( $dummy_info['include_attachments'] ) ) : ?>

	<div class="dt-dummy-controls-block dt-dummy-info-content">
		<p><strong><?php esc_html_e( 'Please note that all copyrighted images were replaced with a placeholder pictures.', 'the7mk2' ); ?></strong></p>
	</div>

<?php endif; ?>

	<div class="dt-dummy-controls-block">
		<div class="dt-dummy-field">
			<label><input type="checkbox" name="import_post_types" checked="checked" value="1" /><?php esc_html_e( 'Import the entire content', 'the7mk2' ); ?></label><span class="dt-dummy-checkbox-desc"><?php esc_html_e( '(Note that this will automatically switch your active Menu and Homepage.)', 'the7mk2' ); ?></span>
		</div>
		<div class="dt-dummy-field">
			<label><input type="checkbox" name="import_theme_options" value="1" /><?php _e( 'Import Theme Options', 'the7mk2' ); ?></label><span class="dt-dummy-checkbox-desc"><?php printf( strip_tags( __( '(Attention! That this will overwrite your current Theme Options and widget areas. You may want to %1$sexport%2$s them before proceeding.)', 'the7mk2' ) ), '<a href="' . admin_url( 'admin.php?page=of-importexport-menu' ) . '" target="_blank">', '</a>' ); ?></span>
		</div>
		<div class="dt-dummy-field">
			<label><input type="checkbox" name="import_attachments" checked="checked" value="1" /><?php _e( 'Download and import file attachments', 'the7mk2' ); ?></label>
		</div>
        <div class="dt-dummy-field">
            <label><input type="checkbox" name="import_rev_sliders" checked="checked" value="1" /><?php _e( 'Import slider(s)', 'the7mk2' ); ?></label>
        </div>
	</div>
	<div class="dt-dummy-controls-block">
		<h4><?php _e( 'Assign posts to an existing user:', 'the7mk2' ); ?></h4>

		<?php wp_dropdown_users( array(
			'class' => 'dt-dummy-content-user',
			'selected' => get_current_user_id(),
		) ); ?>

	</div>
	<div class="dt-dummy-controls-block dt-dummy-control-buttons">
		<div class="dt-dummy-button-wrap">
			<a href="#" class="button button-primary dt-dummy-button-import"><?php _e( 'Import content', 'the7mk2' ); ?></a><span class="spinner"></span>
		</div>
	</div>

<?php if ( ! empty( $dummy_info['bottom_content'] ) ) : ?>

	<div class="dt-dummy-controls-block dt-dummy-info-content">
		<?php echo wp_kses_post( $dummy_info['bottom_content'] ); ?>
	</div>

<?php endif; ?>