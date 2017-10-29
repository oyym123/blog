<?php

// File Security Check.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once trailingslashit( PRESSCORE_SHORTCODES_INCLUDES_DIR ) . 'abstract-dt-shortcode-with-inline-css.php';
require_once trailingslashit( PRESSCORE_SHORTCODES_INCLUDES_DIR ) . 'class-dt-blog-lessvars-manager.php';
require_once trailingslashit( PRESSCORE_SHORTCODES_INCLUDES_DIR ) . 'class-dt-blog-shortcode-html.php';

if ( ! class_exists( 'DT_Shortcode_BlogMasonry', false ) ):

	class DT_Shortcode_BlogMasonry extends DT_Shortcode_With_Inline_Css {

		/**
		 * @var string
		 */
		protected $post_type;

		/**
		 * @var string
		 */
		protected $taxonomy;

		/**
		 * @var DT_Shortcode_BlogMasonry
		 */
		public static $instance = null;

		/**
		 * @return DT_Shortcode_BlogMasonry
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * DT_Shortcode_BlogMasonry constructor.
		 */
		public function __construct() {
			$this->sc_name = 'dt_blog_masonry';
			$this->unique_class_base = 'blog-masonry-shortcode-id';
			$this->taxonomy = 'category';
			$this->post_type = 'post';
			$this->default_atts = array(
				'category' => '',
				'mode' => 'masonry',
				'layout' => 'classic',
				'bo_content_width' => '75%',
				'bo_content_overlap' => '100px',
				'grovly_content_overlap' => '0px',
				'content_bg' => 'y',
				'custom_content_bg_color' => '',
				'post_content_paddings' => '25px 30px 30px 30px',
				'gap_between_posts' => '15px',
				'image_sizing' => 'resize',
				'resized_image_dimensions' => '3x2',
				'image_paddings' => '0px 0px 0px 0px',
				'image_scale_animation_on_hover' => 'y',
				'image_hover_bg_color' => 'y',
				'responsiveness' => 'browser_width_based',
				'bwb_columns' => 'desktop:4|h_tablet:3|v_tablet:2|phone:1',
				'pwb_column_min_width' => '',
				'pwb_columns' => '',
				'all_posts_the_same_width' => 'n',
				'loading_mode' => 'disabled',
				'dis_posts_total' => '-1',
				'st_posts_per_page' => '',
				'st_show_all_pages' => 'n',
				'st_gap_before_pagination' => '50px',
				'jsp_posts_total' => '-1',
				'jsp_posts_per_page' => '',
				'jsp_show_all_pages' => 'n',
				'jsp_gap_before_pagination' => '50px',
				'jsm_posts_total' => '-1',
				'jsm_posts_per_page' => '',
				'jsm_gap_before_pagination' => '50px',
				'jsl_posts_total' => '-1',
				'jsl_posts_per_page' => '',
				'content_alignment' => 'left',
				'post_title_font_style' => '',
				'post_title_font_size' => '',
				'post_title_line_height' => '',
				'custom_title_color' => '',
				'post_title_bottom_margin' => '5px',
				'post_date' => 'y',
				'post_category' => 'y',
				'post_author' => 'y',
				'post_comments' => 'y',
				'meta_info_font_style' => '',
				'meta_info_font_size' => '',
				'meta_info_line_height' => '',
				'custom_meta_color' => '',
				'meta_info_bottom_margin' => '15px',
				'post_content' => 'show_excerpt',
				'excerpt_words_limit' => '',
				'content_font_style' => '',
				'content_font_size' => '',
				'content_line_height' => '',
				'custom_content_color' => '',
				'content_bottom_margin' => '5px',
				'read_more_button' => 'default_link',
				'read_more_button_text' => 'Read more',
				'fancy_date' => 'n',
				'fancy_date_font_color' => '',
				'fancy_date_bg_color' => '',
				'fancy_date_line_color' => '',
				'fancy_categories' => 'n',
				'fancy_categories_font_color' => '',
				'fancy_categories_bg_color' => '',
				'order' => 'desc',
				'orderby' => 'date',
				'show_categories_filter' => 'n',
				'show_orderby_filter' => 'n',
				'show_order_filter' => 'n',
				'gap_below_category_filter' => '50px',
				'navigation_font_color' => '',
				'navigation_accent_color' => '',
			);

			parent::__construct();
		}

		/**
		 * Do shortcode here.
		 */
		protected function do_shortcode( $atts, $content = '' ) {
			// Loop query.
			$query = $this->get_posts_by_terms( $this->get_query_args() );

			do_action( 'presscore_before_shortcode_loop', $this->sc_name, $this->atts );

			// Remove default masonry posts wrap.
			presscore_remove_posts_masonry_wrap();

			$loading_mode = $this->get_att( 'loading_mode' );

			$data_post_limit = '-1';
			switch ( $loading_mode ) {
				case 'js_pagination':
					$data_post_limit = $this->get_att( 'jsp_posts_per_page', get_option( 'posts_per_page' ) );
					break;
				case 'js_more':
					$data_post_limit = $this->get_att( 'jsm_posts_per_page', get_option( 'posts_per_page' ) );
					break;
				case 'js_lazy_loading':
					$data_post_limit = $this->get_att( 'jsl_posts_per_page', get_option( 'posts_per_page' ) );
					break;
			}

			if ( 'disabled' == $loading_mode ) {
				$data_pagination_mode = 'none';
			} else if ( in_array( $loading_mode, array( 'js_more', 'js_lazy_loading' ) ) ) {
				$data_pagination_mode = 'load-more';
			} else {
				$data_pagination_mode = 'pages';
			}

			$data_atts = array(
				'data-post-limit="' . intval( $data_post_limit ) . '"',
				'data-pagination-mode="' . esc_attr( $data_pagination_mode ) . '"',
			);
			$data_atts = $this->add_responsiveness_data_atts( $data_atts );

			echo '<div ' . $this->container_class( 'blog-shortcode' ) . presscore_masonry_container_data_atts( $data_atts ) . '>';

			// Posts filter.
			$filter_class = array( 'iso-filter' );
			if ( 'standard' == $loading_mode ) {
				$filter_class[] = 'without-isotope';
			}

			if ( ! $this->get_flag( 'show_orderby_filter' ) && ! $this->get_flag( 'show_order_filter' ) ) {
				$filter_class[] = 'extras-off';
			}

			$config = presscore_config();

			switch ( $config->get( 'template.posts_filter.style' ) ) {
				case 'minimal':
					$filter_class[] = 'filter-bg-decoration';
					break;
				case 'material':
					$filter_class[] = 'filter-underline-decoration';
					break;
			}

			$terms = array();
			if ( $this->get_flag( 'show_categories_filter' ) ) {
				if ( 'standard' == $loading_mode ) {
					$terms_args = array(
						'taxonomy' => $this->taxonomy,
						'hide_empty' => true,
					);
					$category_att = $this->get_att( 'category' );
					if ( $category_att ) {
						$terms_args['slug'] = presscore_sanitize_explode_string( $category_att );
					}
					$terms = get_terms( $terms_args );
				} else {
					$post_ids = wp_list_pluck( $query->posts, 'ID' );
					$terms = wp_get_object_terms( $post_ids, $this->taxonomy, array( 'fields' => 'all_with_object_id' ) );
				}
			}

			DT_Blog_Shortcode_HTML::display_posts_filter( $terms, $filter_class );

			/**
			 * Blog posts have a custom lazy loading classes.
			 * @see DT_Blog_Shortcode_HTML::get_post_image
			 */
			presscore_remove_lazy_load_attrs();

			echo '<div ' . $this->iso_container_class() . '>';

			// Start loop.
			if ( $query->have_posts() ): while( $query->have_posts() ): $query->the_post();

				do_action('presscore_before_post');

				remove_filter( 'presscore_post_details_link', 'presscore_return_empty_string', 15 );

				// populate config with current post settings
				presscore_populate_post_config();

				// Post visibility on the first page.
				$visibility = 'visible';
				if ( $data_post_limit >= 0 && $query->current_post >= $data_post_limit ) {
					$visibility = 'hidden';
				}

				$post_class_array = array(
					'post',
					'project-odd',
					'visible',
				);

				echo '<div ' . presscore_tpl_masonry_item_wrap_class( $visibility ) . presscore_tpl_masonry_item_wrap_data_attr() . '>';
				echo '<article ' . $this->post_class( $post_class_array ) . ' data-name="' . esc_attr( get_the_title() ) . '" data-date="' . esc_attr( get_the_date( 'c' ) ) . '">';

				// Print custom css for VC scripts.
				if ( 'show_content' === $this->get_att( 'post_content' ) && function_exists( 'visual_composer' ) ) {
					visual_composer()->addShortcodesCustomCss();
				}

				// Post media.
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
					$thumb_args['img_class'] = 'lazy-load';
					$thumb_args['class'] .= ' layzr-bg';
				}

				$post_media = dt_get_thumb_img( $thumb_args );

				$details_btn_style = $this->get_att( 'read_more_button' );
				$details_btn_text = $this->get_att( 'read_more_button_text' );
				$details_btn_class = ('default_button' === $details_btn_style ? array( 'dt-btn-s', 'dt-btn' ) : array());

				presscore_get_template_part( 'shortcodes', 'blog-masonry/tpl-layout', $this->get_att( 'layout' ), array(
					'post_media' => $post_media,
					'details_btn' => DT_Blog_Shortcode_HTML::get_details_btn( $details_btn_style, $details_btn_text, $details_btn_class ),
					'post_excerpt' => $this->get_post_excerpt(),
					'fancy_category_args' => array(
						'custom_text_color' => ( ! $this->get_att( 'fancy_categories_font_color' ) ),
						'custom_bg_color' => ( ! $this->get_att( 'fancy_categories_bg_color' ) ),
					),
				) );

				echo '</article>';
				echo '</div>';

				do_action('presscore_after_post');

			endwhile; endif;

			echo '</div><!-- iso-container|iso-grid -->';

			presscore_add_lazy_load_attrs();

			if ( 'disabled' == $loading_mode ) {
				// Do not output pagination.
			} else if ( in_array( $loading_mode, array( 'js_more', 'js_lazy_loading' ) ) ) {
				// JS load more.
				echo dt_get_next_page_button( 2, 'paginator paginator-more-button' );
			} else if ( 'js_pagination' == $loading_mode ) {
				// JS pagination.
				echo '<div class="paginator" role="navigation"></div>';
			} else {
				// Pagination.
				dt_paginator( $query, array( 'class' => 'paginator' ) );
			}

			echo '</div>';

			do_action( 'presscore_after_shortcode_loop', $this->sc_name, $this->atts );
		}

		/**
		 * Return post excerpt with $length words.
		 *
		 * @return mixed
		 */
		protected function get_post_excerpt() {
			if ( 'off' === $this->atts['post_content'] ) {
				return '';
			}

			if ( 'show_content' === $this->atts['post_content'] ) {
				return apply_filters( 'the_content', get_the_content( '' ) );
			}

			$length = absint( $this->atts['excerpt_words_limit'] );
			$excerpt = get_the_excerpt();

			// VC excerpt fix.
			if ( function_exists( 'vc_manager' ) ) {
				$excerpt = vc_manager()->vc()->excerptFilter( $excerpt );
			}

			if ( $length ) {
				$excerpt = wp_trim_words( $excerpt, $length );
			}

			return apply_filters( 'the_excerpt', $excerpt );
		}

		/**
		 * Return container class attribute.
		 *
		 * @param array $class
		 *
		 * @return string
		 */
		protected function container_class( $class = array() ) {
			if ( ! is_array( $class ) ) {
				$class = explode( ' ', $class );
			}

			// Unique class.
			$class[] = $this->get_unique_class();

			$mode_classes = array(
				'masonry' => 'mode-masonry',
				'grid' => 'mode-grid',
			);

			$mode = $this->get_att( 'mode' );
			if ( array_key_exists( $mode, $mode_classes ) ) {
				$class[] = $mode_classes[ $mode ];
			}

			$layout_classes = array(
				'classic' => 'classic-layout-list',
				'bottom_overlap' => 'bottom-overlap-layout-list',
				'gradient_overlap' => 'gradient-overlap-layout-list',
				'gradient_overlay' => 'gradient-overlay-layout-list',
				'gradient_rollover' => 'content-rollover-layout-list',
			);

			$layout = $this->get_att( 'layout' );
			if ( array_key_exists( $layout, $layout_classes ) ) {
				$class[] = $layout_classes[ $layout ];
			}

			if ( $this->get_flag( 'content_bg' ) ) {
				$class[] = 'content-bg-on';
			}
			

			$loading_mode = $this->get_att( 'loading_mode' );
			if ( 'standard' !== $loading_mode ) {
				$class[] = 'jquery-filter';
			}

			if ( 'js_lazy_loading' === $loading_mode ) {
				$class[] = 'lazy-loading-mode';
			}

			if ( $this->get_flag( 'jsp_show_all_pages' ) ) {
				$class[] = 'show-all-pages';
			}

			if ( $this->get_flag( 'fancy_date' ) ) {
				$class[] = presscore_blog_fancy_date_class();
			}

			if ( 'center' === $this->get_att( 'content_alignment' ) ) {
				$class[] = 'content-align-center';
			}

			if ( $this->get_flag( 'image_scale_animation_on_hover' ) ) {
				$class[] = 'scale-img';
			}

			if ( ! ( $this->get_flag( 'post_date' ) || $this->get_flag( 'post_category' ) || $this->get_flag( 'post_comments' ) || $this->get_flag( 'post_author' ) ) ) {
				$class[] = 'meta-info-off';
			}

			if ( in_array( $layout, array( 'gradient_overlay', 'gradient_rollover' ) ) && 'off' === $this->get_att( 'post_content' ) && 'off' === $this->get_att( 'read_more_button' ) ) {
				$class[] = 'disable-layout-hover';
			}
	

			if ( $this->get_flag( 'image_hover_bg_color' ) ) {
				$class[] = 'enable-bg-rollover';
			}

			$class = $this->add_responsiveness_class( $class );

			// Dirty hack to remove .iso-container and .iso-grid
			$config = presscore_config();
			$layout = $config->get( 'layout' );
			$config->set( 'layout', false );

			$class_str = presscore_masonry_container_class( $class );
			$class_str = str_replace( 'content-align-centre', '', $class_str );

			// Restore original 'layout'.
			$config->set( 'layout', $layout );

			return $class_str;
		}

		/**
		 * Iso container class.
		 *
		 * @param array $class
		 *
		 * @return string
		 */
		protected function iso_container_class( $class = array() ) {
			if ( 'grid' === $this->get_att( 'mode' ) ) {
				$class[] = 'iso-grid';
			} else {
				$class[] = 'iso-container';
			}

			return 'class="' . esc_attr( join( ' ', $class ) ) . '" ';
		}

		/**
		 * Return post classes.
		 *
		 * @param string|array $class
		 *
		 * @return string
		 */
		protected function post_class( $class = array() ) {
			if ( ! is_array( $class ) ) {
				$class = explode( ' ', $class );
			}

			return 'class="' . join( ' ', get_post_class( $class, null ) ) . '"';
		}

		/**
		 * Browser responsiveness classes.
		 *
		 * @param array $class
		 *
		 * @return array
		 */
		protected function add_responsiveness_class( $class = array() ) {
			if ( 'browser_width_based' === $this->get_att( 'responsiveness' ) ) {
				$class[] = 'resize-by-browser-width';
			}

			return $class;
		}

		/**
		 * Browser responsiveness data attributes.
		 *
		 * @param array $data_atts
		 *
		 * @return array
		 */
		protected function add_responsiveness_data_atts( $data_atts = array() ) {
			if ( 'browser_width_based' === $this->get_att( 'responsiveness' ) ) {
				$bwb_columns = DT_VCResponsiveColumnsParam::decode_columns( $this->get_att( 'bwb_columns' ) );
				$columns = array(
					'desktop' => 'desktop',
					'v_tablet' => 'v-tablet',
					'h_tablet' => 'h-tablet',
					'phone' => 'phone',
				);

				foreach ( $columns as $column => $data_att ) {
					$val = ( isset( $bwb_columns[ $column ] ) ? absint( $bwb_columns[ $column ] ) : '' );
					$data_atts[] = 'data-' . $data_att . '-columns-num="' . esc_attr( $val ) . '"';
				}
			}

			return $data_atts;
		}

		/**
		 * Return shortcode less file absolute path to output inline.
		 *
		 * @return string
		 */
		protected function get_less_file_name() {
			return get_template_directory() . '/css/dynamic-less/shortcodes/blog.less';
		}

		/**
		 * Setup theme config for shortcode.
		 */
		protected function setup_config() {
			$config = presscore_config();

			$config->set( 'load_style', 'default' );
			$config->set( 'template', 'blog' );
			$config->set( 'layout', $this->get_att( 'mode' ) );
			$config->set( 'all_the_same_width', $this->get_flag( 'all_posts_the_same_width' ) );
			$show_post_content = ( 'off' !== $this->get_att( 'post_content' ) );
			$config->set( 'show_excerpts', $show_post_content );
			$config->set( 'post.preview.content.visible', $show_post_content );
			$config->set( 'show_details', ( 'off' !== $this->get_att( 'read_more_button' ) ) );
			$config->set( 'image_layout', ( 'resize' === $this->get_att( 'image_sizing' ) ? $this->get_att( 'image_sizing' ) : 'original' ) );

			if ( 'resize' == $this->get_att( 'image_sizing' ) && $this->get_att( 'resized_image_dimensions' ) ) {
				// Sanitize.
				$img_dim = array_slice( array_map( 'absint', explode( 'x', strtolower( $this->get_att( 'resized_image_dimensions' ) ) ) ), 0, 2 );
				// Make sure that all values is set.
				for ( $i = 0; $i < 2; $i++ ) {
					if ( empty( $img_dim[ $i ] ) ) {
						$img_dim[ $i ] = '';
					}
				}
				$config->set( 'thumb_proportions', array( 'width' => $img_dim[0], 'height' => $img_dim[1] ) );
			} else {
				$config->set( 'thumb_proportions', '' );
			}

			if ( in_array( $this->get_att( 'layout' ), array( 'classic', 'bottom_overlap', 'gradient_overlap' ) ) ) {
				$config->set( 'post.preview.description.style', 'under_image' );
			}

			$config->set( 'post.meta.fields.date', $this->get_flag( 'post_date' ) );
			$config->set( 'post.meta.fields.categories', $this->get_flag( 'post_category' ) );
			$config->set( 'post.meta.fields.comments', $this->get_flag( 'post_comments' ) );
			$config->set( 'post.meta.fields.author', $this->get_flag( 'post_author' ) );

			$config->set( 'post.fancy_date.enabled', $this->get_flag( 'fancy_date' ) );
			$config->set( 'post.fancy_category.enabled', $this->get_flag( 'fancy_categories' ) );
			$config->set( 'post.preview.background.enabled', false );
			$config->set( 'post.preview.background.style',  '' );
			$config->set( 'post.preview.media.width', 30 );
			$config->set( 'post.preview.load.effect', 'fade_in' );
			$config->set( 'post.preview.width.min', $this->get_att( 'pwb_column_min_width' ) );

			$config->set( 'template.columns.number', $this->get_att( 'pwb_columns' ) );
			$config->set( 'template.posts_filter.terms.enabled', $this->get_flag( 'show_categories_filter' ) );
			$config->set( 'template.posts_filter.orderby.enabled', $this->get_flag( 'show_orderby_filter' ) );
			$config->set( 'template.posts_filter.order.enabled', $this->get_flag( 'show_order_filter' ) );

			if ( 'standard' === $this->get_att( 'loading_mode' ) ) {
				$config->set( 'show_all_pages', $this->get_flag( 'st_show_all_pages' ) );

				// Allow sorting from request.
				if ( ! $config->get('order') ) {
					$config->set( 'order', $this->get_att( 'order' ) );
				}

				if ( ! $config->get('orderby') ) {
					$config->set( 'orderby', $this->get_att( 'orderby' ) );
				}
			} else {
				$config->set( 'show_all_pages', $this->get_flag( 'jsp_show_all_pages' ) );

				$config->set( 'request_display', false );
				$config->set( 'order', $this->get_att( 'order' ) );
				$config->set( 'orderby', $this->get_att( 'orderby' ) );
			}

			$config->set( 'item_padding', $this->get_att( 'gap_between_posts' ) );

			// Get terms ids.
			$terms = get_terms( array(
				'taxonomy' => 'category',
				'slug' => presscore_sanitize_explode_string( $this->get_att( 'category' ) ),
				'fields' => 'ids',
			) );

			$config->set( 'display', array(
				'type' => 'category',
				'terms_ids' => $terms,
				'select' => ( $terms ? 'only' : 'all' ),
			) );
		}

		/**
		 * Return array of prepared less vars to insert to less file.
		 *
		 * @return array
		 */
		protected function get_less_vars() {
			$storage = new Presscore_Lib_SimpleBag();
			$factory = new Presscore_Lib_LessVars_Factory();
			$less_vars = new DT_Blog_LessVars_Manager( $storage, $factory );

			$less_vars->add_keyword( 'unique-shortcode-class-name', 'blog-shortcode.' . $this->get_unique_class(), '~"%s"' );

			$less_vars->add_pixel_or_percent_number( 'post-content-width', $this->get_att( 'bo_content_width' ) );
			$less_vars->add_pixel_number( 'post-content-top-overlap', $this->get_att( 'bo_content_overlap' ) );
			$less_vars->add_pixel_or_percent_number( 'post-content-overlap', $this->get_att( 'grovly_content_overlap' ) );
			$less_vars->add_keyword( 'post-title-color', $this->get_att( 'custom_title_color', '~""' ) );
			$less_vars->add_keyword( 'post-meta-color', $this->get_att( 'custom_meta_color', '~""' ) );
			$less_vars->add_keyword( 'post-content-color', $this->get_att( 'custom_content_color', '~""' ) );
			$less_vars->add_keyword( 'post-content-bg', $this->get_att( 'custom_content_bg_color', '~""' ) );

			$less_vars->add_paddings( array(
				'post-thumb-padding-top',
				'post-thumb-padding-right',
				'post-thumb-padding-bottom',
				'post-thumb-padding-left',
			), $this->get_att( 'image_paddings' ), '%|px' );

			$less_vars->add_keyword( 'fancy-data-color', $this->get_att( 'fancy_date_font_color', '~""' ) );
			$less_vars->add_keyword( 'fancy-data-bg', $this->get_att( 'fancy_date_bg_color', '~""' ) );
			$less_vars->add_keyword( 'fancy-data-line-color', $this->get_att( 'fancy_date_line_color', '~""' ) );
			$less_vars->add_keyword( 'fancy-category-color', $this->get_att( 'fancy_categories_font_color', '~""' ) );
			$less_vars->add_keyword( 'fancy-category-bg', $this->get_att( 'fancy_categories_bg_color', '~""' ) );

			$less_vars->add_paddings( array(
				'post-content-padding-top',
				'post-content-padding-right',
				'post-content-padding-bottom',
				'post-content-padding-left',
			), $this->get_att( 'post_content_paddings' ) );

			$less_vars->add_pixel_number( 'post-title-font-size', $this->get_att( 'post_title_font_size' ) );
			$less_vars->add_pixel_number( 'post-title-line-height', $this->get_att( 'post_title_line_height' ) );
			$less_vars->add_pixel_number( 'post-meta-font-size', $this->get_att( 'meta_info_font_size' ) );
			$less_vars->add_pixel_number( 'post-meta-line-height', $this->get_att( 'meta_info_line_height' ) );

			$less_vars->add_pixel_number( 'post-excerpt-font-size', $this->get_att( 'content_font_size' ) );
			$less_vars->add_pixel_number( 'post-excerpt-line-height', $this->get_att( 'content_line_height' ) );
			$less_vars->add_pixel_number( 'post-meta-margin-bottom', $this->get_att( 'meta_info_bottom_margin' ) );
			$less_vars->add_pixel_number( 'post-title-margin-bottom', $this->get_att( 'post_title_bottom_margin' ) );
			$less_vars->add_pixel_number( 'post-excerpt-margin-bottom', $this->get_att( 'content_bottom_margin' ) );
			$less_vars->add_font_style( array(
				'post-title-font-style',
				'post-title-font-weight',
				'post-title-text-transform',
			), $this->get_att( 'post_title_font_style' ) );
			$less_vars->add_font_style( array(
				'post-meta-font-style',
				'post-meta-font-weight',
				'post-meta-text-transform',
			), $this->get_att( 'meta_info_font_style' ) );
			$less_vars->add_font_style( array(
				'post-content-font-style',
				'post-content-font-weight',
				'post-content-text-transform',
			), $this->get_att( 'content_font_style' ) );
			$less_vars->add_pixel_number( 'shortcode-filter-gap', $this->get_att( 'gap_below_category_filter', '' ) );
			$less_vars->add_keyword( 'shortcode-filter-color', $this->get_att( 'navigation_font_color', '~""' ) );
			$less_vars->add_keyword( 'shortcode-filter-accent', $this->get_att( 'navigation_accent_color', '~""' ) );

			$gap_before_pagination = '';
			switch ( $this->get_att( 'loading_mode' ) ) {
				case 'standard':
					$gap_before_pagination = $this->get_att( 'st_gap_before_pagination', '' );
					break;
				case 'js_pagination':
					$gap_before_pagination = $this->get_att( 'jsp_gap_before_pagination', '' );
					break;
				case 'js_more':
					$gap_before_pagination = $this->get_att( 'jsm_gap_before_pagination', '' );
					break;
			}
			$less_vars->add_pixel_number( 'shortcode-pagination-gap', $gap_before_pagination );

			return $less_vars->get_vars();
		}

		/**
		 * Return dummy html for VC inline editor.
		 *
		 * @return string
		 */
		protected function get_vc_inline_html() {
			$terms_title = _x( 'Display categories', 'vc inline dummy', 'the7mk2' );

			return $this->vc_inline_dummy( array(
				'class' => 'dt_vc-blog_masonry',
				'title' => _x( 'NEW Blog Masonry & Grid', 'vc inline dummy', 'the7mk2' ),
				'fields' => array(
					$terms_title => presscore_get_terms_list_by_slug( array( 'slugs' => $this->atts['category'], 'taxonomy' => 'category' ) ),
				),
			) );
		}

		/**
		 * Return query args.
		 *
		 * @return array
		 */
		protected function get_query_args() {
			$pagination_mode = $this->get_att( 'loading_mode' );
			$posts_total = -1;
			switch ( $pagination_mode ) {
				case 'disabled':
					$posts_total = $this->get_att( 'dis_posts_total' );
					break;
				case 'standard':
					$posts_total = $this->get_att( 'st_posts_per_page' );
					break;
				case 'js_pagination':
					$posts_total = $this->get_att( 'jsp_posts_total' );
					break;
				case 'js_more':
					$posts_total = $this->get_att( 'jsm_posts_total' );
					break;
				case 'js_lazy_loading':
					$posts_total = $this->get_att( 'jsl_posts_total' );
					break;
			}

			$category_att = $this->get_att( 'category' );
			$terms_slugs = '';
			if ( $category_att ) {
				$terms_slugs = presscore_sanitize_explode_string( $category_att );
			}

			$query_args =  array(
				'orderby' => $this->get_att( 'orderby' ),
				'order' => $this->get_att( 'order' ),
				'number' => $posts_total,
				'select' => ( $terms_slugs ? 'only' : 'all' ),
				'category' => $terms_slugs,
			);

			// For standard pagination mode.
			if ( 'standard' == $pagination_mode ) {
				$config = presscore_config();
				$query_args['orderby'] = $config->get( 'orderby' );
				$query_args['order'] = $config->get( 'order' );
				$query_args['paged'] = dt_get_paged_var();

				$request = $config->get( 'request_display' );
				if ( $request ) {
					$query_args['select'] = $request['select'];
					$terms = get_terms( array(
						'taxonomy' => 'category',
						'include' => $request['terms_ids'],
						'fields' => 'id=>slug',
					) );
					if ( ! is_wp_error( $terms ) ) {
						$query_args['category'] = array_values( $terms );
					}
				}
			}

			return $query_args;
		}
	}

	DT_Shortcode_BlogMasonry::get_instance()->add_shortcode();

endif;
