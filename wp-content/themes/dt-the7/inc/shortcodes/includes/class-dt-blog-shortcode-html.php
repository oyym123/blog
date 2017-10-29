<?php
// File Security Check.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class DT_Blog_Shortcode_HTML
 */
class DT_Blog_Shortcode_HTML {

	/**
	 * Return "Details" button HTML.
	 *
	 * @param string       $btn_style
	 * @param string|null  $btn_text
	 * @param array|string $class
	 *
	 * @return string
	 */
	public static function get_details_btn( $btn_style = 'default', $btn_text = '', $class = array() ) {
		if ( ! is_array( $class ) ) {
			$class = explode( ' ', $class );
		}

		$class[] = 'post-details';

		$btn_classes = array(
			'default_link' => 'details-type-link',
			'default_button' => 'details-type-btn',
		);

		if ( isset( $btn_classes[ $btn_style ] ) ) {
			$class[] = $btn_classes[ $btn_style ];
		}

		$btn_text .= '<i class="fa fa-caret-right" aria-hidden="true"></i>';

		return presscore_post_details_link( null, $class, $btn_text );
	}

	/**
	 * Return post image HTML.
	 *
	 * @return bool|mixed|string
	 */
	public static function get_post_image() {
		$thumb_args = apply_filters( 'dt_post_thumbnail_args', array(
			'img_id'	=> get_post_thumbnail_id(),
			'class'		=> 'post-thumbnail-rollover',
			'href'		=> get_permalink(),
			'wrap'		=> '<a %HREF% %CLASS% %CUSTOM%><img %IMG_CLASS% %SRC% %ALT% %IMG_TITLE% %SIZE% /></a>',
			'echo'      => false,
		) );

		// Custom lazy loading classes.
		if ( presscore_lazy_loading_enabled() ) {
			$thumb_args['lazy_loading'] = true;
			$thumb_args['img_class'] = 'blog-thumb-lazy-load';
			$thumb_args['class'] .= ' layzr-bg';
		}

		return dt_get_thumb_img( $thumb_args );
	}

	/**
	 * Output posts filter.
	 *
	 * @param array $terms
	 * @param array $class
	 */
	public static function display_posts_filter( $terms, $class = array() ) {
		if ( ! is_array( $class ) ) {
			$class = explode( ' ', $class );
		}

		$class[] = 'filter';

		presscore_get_category_list( array(
			'data'  => array(
				'terms'       => $terms,
				'all_count'   => false,
				'other_count' => false,
			),
			'class' => implode( ' ', $class ),
		) );
	}
}