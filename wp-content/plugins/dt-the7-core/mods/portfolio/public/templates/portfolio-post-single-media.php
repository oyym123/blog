<?php
/**
 * Project single media content part
 *
 * @package vogue
 * @since 1.0.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

$config = Presscore_Config::get_instance();

if ( 'disabled' != $config->get( 'post.media.layout' ) ) {

	// get media
	$media_items = $config->get( 'post.media.library' );

	if ( !$media_items ) $media_items = array();

	// if we have post thumbnail and it's not hidden
	if ( has_post_thumbnail() && $config->get( 'post.media.featured_image.enabled' ) ) {
		array_unshift( $media_items, absint( get_post_thumbnail_id() ) );
	}

	$open_thumbnail_in_lightbox = $config->get( 'post.media.lightbox.enabled' );
	$media_type = $config->get( 'post.media.type' );
	$attachments_data = presscore_get_attachment_post_data( $media_items );
	$attachments_count = count( $attachments_data );
	$wrap_media = true;

	if ( $attachments_count > 1 && 'gallery' == $media_type ) {

		$gallery_columns = absint( $config->get( 'post.media.gallery.columns' ) );
		$gallery_columns = $gallery_columns ? $gallery_columns : 4;

		$media_html = presscore_get_images_gallery_1( $attachments_data, array(
			'columns' => $gallery_columns,
			'first_big' => $config->get( 'post.media.gallery.first_iamge_is_large' )
		) );

	} elseif ( $attachments_count > 1 && 'slideshow' == $media_type ) {

		// slideshow dimensions
		$slider_proportions = $config->get( 'post.media.slider.proportion' );
		if ( !is_array( $slider_proportions ) ) {
			$slider_proportions = array( 'width' => '', 'height' => '' );
		}
		$slider_proportions = wp_parse_args( $slider_proportions, array( 'width' => '', 'height' => '' ) );

		// Scale mode.
		$scale_mode = $config->get( 'post.media.slider.scale_mode' );
		$scale_mode = ( 'fit' === $scale_mode ? 'fit' : 'fill' );

		$media_html = presscore_get_photo_slider( $attachments_data, array(
			'class' 	=> array('slider-post'),
			'width' 	=> absint( $slider_proportions['width'] ),
			'height'	=> absint( $slider_proportions['height'] ),
			'style'		=> ' style="width: 100%;" data-img-mode="' . $scale_mode . '"',
		) );

		// Do not wrap.
		$wrap_media = false;

	} elseif ( 'list' == $media_type ) {

		$media_html = presscore_get_images_list( $attachments_data, array(
			'open_in_lightbox' => $open_thumbnail_in_lightbox
		) );

	} else {

		$one_image_params = array();

		if ( ! $open_thumbnail_in_lightbox ) {
			$one_image_params['wrap'] = '<img %IMG_CLASS% %SRC% %IMG_TITLE% %ALT% %SIZE% />';
		}

		$media_html = presscore_get_post_attachment_html( current( $attachments_data ), $one_image_params );

	}

	if ( $media_html && $wrap_media ) {
		$media_html = '<div class="images-container">' . $media_html . '</div>';
	}

	echo $media_html;

}
