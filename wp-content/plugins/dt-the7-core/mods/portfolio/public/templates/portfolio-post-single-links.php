<?php
/**
 * Portfolio project single links content part
 */

// File Security Check.
if ( ! defined( 'ABSPATH' ) ) { exit; }

wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'dt-the7-core' ), 'after' => '</div>', 'echo' => false ) );

$project_link = presscore_get_project_link( 'btn-project-link', '<i class="fa fa-external-link-square" aria-hidden="true"></i>&nbsp;' );
$post_meta = presscore_get_single_posted_on();
if ( $project_link || $post_meta ) {
	echo '<div class="project-info-box">' . $project_link . $post_meta . '</div>';
}

presscore_display_share_buttons_for_post( 'portfolio_post' );

echo presscore_new_post_navigation( array(
	'prev_src_text'      => __( 'Previous project:', 'dt-the7-core' ),
	'next_src_text'      => __( 'Next project:', 'dt-the7-core' ),
	'taxonomy'           => 'dt_portfolio_category',
	'screen_reader_text' => __( 'Project navigation', 'dt-the7-core' ),
) );
