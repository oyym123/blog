<?php
/**
 * Testimonials admin part.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Presscore_Mod_Testimonials_Admin {

	public function register_post_types() {
		$post_type = 'dt_testimonials';
		$args = array(
			'labels'                => array(
				'name'                  => _x('Testimonials',						'backend testimonials', 'dt-the7-core'),
				'singular_name'         => _x('Testimonials',						'backend testimonials', 'dt-the7-core'),
				'add_new'               => _x('Add New Testimonial',				'backend testimonials', 'dt-the7-core'),
				'add_new_item'          => _x('Add New Testimonial',				'backend testimonials', 'dt-the7-core'),
				'edit_item'             => _x('Edit Testimonial',					'backend testimonials', 'dt-the7-core'),
				'new_item'              => _x('New Testimonial',					'backend testimonials', 'dt-the7-core'),
				'view_item'             => _x('View Testimonial',					'backend testimonials', 'dt-the7-core'),
				'search_items'          => _x('Search Testimonials',				'backend testimonials', 'dt-the7-core'),
				'not_found'             => _x('No Testimonials found',				'backend testimonials', 'dt-the7-core'),
				'not_found_in_trash'    => _x('No Testimonials found in Trash',		'backend testimonials', 'dt-the7-core'),
				'parent_item_colon'     => '',
				'menu_name'             => _x('Testimonials',						'backend testimonials', 'dt-the7-core')
			),
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true, 
			'query_var'             => true,
			'rewrite'               => true,
			'capability_type'       => 'post',
			'has_archive'           => true,
			'hierarchical'          => false,
			'menu_position'         => 36,
			'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail' )
		);

		$args = apply_filters( "presscore_post_type_{$post_type}_args", $args );

		register_post_type( $post_type, $args );
	}

	public function register_taxonomies() {
		$post_type = 'dt_testimonials';
		$taxonomy = 'dt_testimonials_category';
		$args = array(
			'labels'                => array(
				'name'              => _x( 'Testimonial Categories',			'backend testimonials', 'dt-the7-core' ),
				'singular_name'     => _x( 'Testimonial Category',				'backend testimonials', 'dt-the7-core' ),
				'search_items'      => _x( 'Search in Category',				'backend testimonials', 'dt-the7-core' ),
				'all_items'         => _x( 'Categories',						'backend testimonials', 'dt-the7-core' ),
				'parent_item'       => _x( 'Parent Category',					'backend testimonials', 'dt-the7-core' ),
				'parent_item_colon' => _x( 'Parent Category:',					'backend testimonials', 'dt-the7-core' ),
				'edit_item'         => _x( 'Edit Category',						'backend testimonials', 'dt-the7-core' ),
				'update_item'       => _x( 'Update Category',					'backend testimonials', 'dt-the7-core' ),
				'add_new_item'      => _x( 'Add New Testimonial Category',		'backend testimonials', 'dt-the7-core' ),
				'new_item_name'     => _x( 'New Category Name',					'backend testimonials', 'dt-the7-core' ),
				'menu_name'         => _x( 'Testimonial Categories',			'backend testimonials', 'dt-the7-core' )
			),
			'hierarchical'          => true,
			'public'                => true,
			'show_ui'               => true,
			'rewrite'               => true,
			'show_admin_column'		=> true,
		);

		$args = apply_filters( "presscore_taxonomy_{$taxonomy}_args", $args );

		register_taxonomy( $taxonomy, array( $post_type ), $args );
	}

	public function add_meta_boxes( $metaboxes ) {
		$metaboxes[] = plugin_dir_path( __FILE__ ) . 'metaboxes/metaboxes-testimonials.php';
		return $metaboxes;
	}

	public function add_basic_meta_boxes_support( $pages ) {
		$pages[] = 'dt_testimonials';
		return $pages;
	}
}
