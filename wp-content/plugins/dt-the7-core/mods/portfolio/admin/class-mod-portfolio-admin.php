<?php
/**
 * Portfolio admin part.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Presscore_Mod_Portfolio_Admin {

	public function register_post_types() {
		$post_type = 'dt_portfolio';
		$args = array(
			'labels'                => array(
				'name'                  => _x( 'Portfolio',              'backend portfolio', 'dt-the7-core' ),
				'singular_name'         => _x( 'Portfolio',              'backend portfolio', 'dt-the7-core' ),
				'add_new'               => _x( 'Add New',                'backend portfolio', 'dt-the7-core' ),
				'add_new_item'          => _x( 'Add New Item',           'backend portfolio', 'dt-the7-core' ),
				'edit_item'             => _x( 'Edit Item',              'backend portfolio', 'dt-the7-core' ),
				'new_item'              => _x( 'New Item',               'backend portfolio', 'dt-the7-core' ),
				'view_item'             => _x( 'View Item',              'backend portfolio', 'dt-the7-core' ),
				'search_items'          => _x( 'Search Items',           'backend portfolio', 'dt-the7-core' ),
				'not_found'             => _x( 'No items found',         'backend portfolio', 'dt-the7-core' ),
				'not_found_in_trash'    => _x( 'No items found in Trash','backend portfolio', 'dt-the7-core' ),
				'parent_item_colon'     => '',
				'menu_name'             => _x( 'Portfolio', 'backend portfolio', 'dt-the7-core' )
			),
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true, 
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'project' ),
			'capability_type'       => 'post',
			'has_archive'           => true, 
			'hierarchical'          => false,
			'menu_position'         => 35,
			'supports'              => array( 'author', 'title', 'editor', 'thumbnail', 'comments', 'excerpt', 'revisions' )
		);

		$args = apply_filters( "presscore_post_type_{$post_type}_args", $args );

		register_post_type( $post_type, $args );
	}

	public function register_taxonomies() {
		$post_type = 'dt_portfolio';
		$taxonomy = 'dt_portfolio_category';
		$args = array(
			'labels'                => array(
				'name'              => _x( 'Portfolio Categories', 'backend portfolio', 'dt-the7-core' ),
				'singular_name'     => _x( 'Portfolio Category', 'backend portfolio', 'dt-the7-core' ),
				'search_items'      => _x( 'Search in Category', 'backend portfolio', 'dt-the7-core' ),
				'all_items'         => _x( 'Portfolio Categories', 'backend portfolio', 'dt-the7-core' ),
				'parent_item'       => _x( 'Parent Portfolio Category', 'backend portfolio', 'dt-the7-core' ),
				'parent_item_colon' => _x( 'Parent Portfolio Category:', 'backend portfolio', 'dt-the7-core' ),
				'edit_item'         => _x( 'Edit Category', 'backend portfolio', 'dt-the7-core' ),
				'update_item'       => _x( 'Update Category', 'backend portfolio', 'dt-the7-core' ),
				'add_new_item'      => _x( 'Add New Portfolio Category', 'backend portfolio', 'dt-the7-core' ),
				'new_item_name'     => _x( 'New Category Name', 'backend portfolio', 'dt-the7-core' ),
				'menu_name'         => _x( 'Portfolio Categories', 'backend portfolio', 'dt-the7-core' )
			),
			'hierarchical'          => true,
			'public'                => true,
			'show_ui'               => true,
			'rewrite'               => array( 'slug' => 'project-category' ),
			'show_admin_column'		=> true,
		);

		$args = apply_filters( "presscore_taxonomy_{$taxonomy}_args", $args );

		register_taxonomy( $taxonomy, array( $post_type ), $args );
	}

	public function add_meta_boxes( $metaboxes ) {
		$metaboxes[] = plugin_dir_path( __FILE__ ) . 'metaboxes/metaboxes-portfolio.php';
		return $metaboxes;
	}

	public function add_basic_meta_boxes_support( $pages ) {
		$pages[] = 'dt_portfolio';
		return $pages;
	}

	public function add_options( $options ) {
		if ( array_key_exists( 'of-blog-and-portfolio-menu', $options ) ) {
			$options['of-portfolio-mod-injected-options'] = plugin_dir_path( __FILE__ ) . 'options/options-portfolio.php';
			$options['of-portfolio-mod-injected-slug-options'] = plugin_dir_path( __FILE__ ) . 'options/options-slug-portfolio.php';
		} else if ( function_exists( 'presscore_module_archive_get_menu_slug' ) && array_key_exists( presscore_module_archive_get_menu_slug(), $options ) ) {
			$options['of-portfolio-mod-injected-archive-options'] = plugin_dir_path( __FILE__ ) . 'options/options-archive-portfolio.php';
		}
		return $options;
	}

	public function js_composer_default_editor_post_types_filter( $post_types ) {
		$post_types[] = 'dt_portfolio';
		return $post_types;
	}
}
