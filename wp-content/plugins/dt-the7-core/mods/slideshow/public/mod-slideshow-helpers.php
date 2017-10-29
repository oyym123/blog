<?php
/**
 * Slideshow helpers.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'presscore_render_porthole_slider_data' ) ) :

	/**
	 * Porthole slider data.
	 *
	 */
	function presscore_render_porthole_slider_data() {
		global $post;
		$config = Presscore_Config::get_instance();

		$slider_id = $config->get('slideshow_sliders');
		$slideshows = presscore_query()->get_posts( array( 'post_type' => 'dt_slideshow', 'post__in' => $slider_id, 'has_password' => false ) );

		if ( !$slideshows || !$slideshows->have_posts() ) return;

		$slides = array();
		foreach ( $slideshows->posts as $slideshow ) {
			$media_items = get_post_meta( $slideshow->ID, '_dt_slider_media_items', true );
			if ( empty($media_items) ) continue;

			$slides = array_merge( $slides, $media_items );
		}
		$slides = array_unique($slides);

		$media_query = presscore_query()->get_attachments( array(
			'posts_per_page'	=> -1,
			'orderby'			=> 'post__in',
			'post__in'			=> $slides,
		) );

		// prepare data
		if ( $media_query->have_posts() ) {

			presscore_remove_lazy_load_attrs();

			echo '<ul id="main-slideshow-content" class="rsHomePorthole">';

			while ( $media_query->have_posts() ) { $media_query->the_post();

				$video_url = get_post_meta( $post->ID, 'dt-video-url', true );
				$img_link = get_post_meta( $post->ID, 'dt-img-link', true );
				$thumb_meta = wp_get_attachment_image_src( $post->ID, 'thumbnail' );
				$hide_title = presscore_imagee_title_is_hidden( $post->ID );

				$img_custom = 'data-rsTmb="' . $thumb_meta[0] . '"';

				$img_args = array(
					'img_meta'	=> wp_get_attachment_image_src( $post->ID, 'full' ),
					'img_id'	=> $post->ID,
					'custom'	=> $img_custom,
					'echo'		=> false,
					'wrap'		=> '<img %IMG_CLASS% %SRC% %CUSTOM% %ALT% %SIZE% />',
				);
				$image = dt_get_thumb_img( $img_args );

				$caption = '';

				if ( !$config->get('slideshow_hide_captions') ) {

					if ( !$hide_title && $title = get_the_title() ) {
						$caption .= '<div class="rsTitle">' . $title . '</div>';
					}

					if ( $content = get_the_content() ) {
						$caption .= '<div class="rsDesc">' . $content . '</div>';
					}

					if ( $caption ) {
						$caption = sprintf( '<figure class="rsCapt rsABlock">%s</figure>', $caption );
					}

					$links = '';
					$links_count = 0;

					if ( $video_url ) {
						$_video_url = remove_query_arg( array('iframe', 'width', 'height'), $video_url );
						$links .= sprintf( '<a class="rsPlayBtn dt-single-mfp-popup dt-mfp-item mfp-iframe" href="%s"><span class="assistive-text">%s</span></a>',
								esc_url( $_video_url ),
								__( 'video', 'dt-the7-core' )
						);
						$links_count++;
					}

					if ( $img_link ) {
						$links .= sprintf( '<a class="rsCLink" href="%s"><span class="assistive-text">%s</span></a>',
							esc_url( $img_link ),
							__( 'details', 'dt-the7-core' )
						);
						$links_count++;
					}

					if ( $links ) {
						$caption = '<div class="rsBtnCenterer' . ( $links_count > 1 ? ' with-link' : '' ) . '">' . $links . '</div>' . $caption;
					}
				}

				printf( '<li>%s</li>', $image . $caption );
			}
			wp_reset_postdata();

			presscore_add_lazy_load_attrs();

			echo '</ul>';
		}
	}

endif;

if ( ! function_exists( 'presscore_render_3d_slider_data' ) ) :

	/**
	 * Render 3D slider.
	 *
	 */
	function presscore_render_3d_slider_data() {
		global $post;
		$config = Presscore_Config::get_instance();

		$slider_id = $config->get('slideshow_sliders');
		$slideshows = presscore_query()->get_posts( array( 'post_type' => 'dt_slideshow', 'post__in' => $slider_id, 'has_password' => false ) );

		if ( !$slideshows || !$slideshows->have_posts() ) {
			return;
		}

		$slides = array();
		foreach ( $slideshows->posts as $slideshow ) {

			$media_items = get_post_meta( $slideshow->ID, '_dt_slider_media_items', true );
			if ( empty($media_items) ) {
				continue;
			}

			$slides = array_merge( $slides, $media_items );
		}

		$attachments_data = presscore_get_attachment_post_data( $slides );

		$count = count($attachments_data);
		if ( $count < 10 ) {

			$chunks = array( $attachments_data, array(), array() );
		} else {

			$length = ceil( $count/3 );
			$chunks = array_chunk( $attachments_data, $length );
		}

		$chunks = array_reverse( $chunks );

		foreach ( $chunks as $layer=>$images ) {

			printf( '<div id="level%d" class="plane">' . "\n", $layer + 1 );

			foreach ( $images as $img ) {
				printf( '<img src="%s" alt="%s" />' . "\n", esc_url($img['full']), esc_attr($img['description']) );
			}

			echo "</div>\n";
		}

	}

endif;
