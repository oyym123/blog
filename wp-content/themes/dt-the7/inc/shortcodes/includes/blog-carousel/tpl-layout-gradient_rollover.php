<?php
/**
 * Masonry blog layout template.
 */

// File Security Check.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$config = presscore_config();
?>

<?php if ( presscore_post_format_supports_media_content( get_post_format() ) ) : ?>

<div class="post-thumbnail-wrap">
	<div class="post-thumbnail">
		<?php echo presscore_get_blog_post_fancy_date(); ?>

		<?php
		if ( $config->get( 'post.fancy_category.enabled' ) ) {
			$fancy_category_args = ( isset( $fancy_category_args ) ? $fancy_category_args : array() );
			echo presscore_get_post_fancy_category( $fancy_category_args );
		}
		?>

		<?php
		if ( isset( $post_media ) ) {
			echo $post_media;
		}
		?>
	</div>
</div>

<?php endif; ?>

<div class="post-entry-content">
	<div class="post-entry-wrapper">

		<h3 class="entry-title">
			<a href="<?php the_permalink(); ?>" title="<?php echo the_title_attribute( 'echo=0' ); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h3>

		<?php echo presscore_get_posted_on(); ?>

		<?php
		if ( $config->get( 'show_excerpts' ) && isset( $post_excerpt ) ) {
			echo '<div class="entry-excerpt">';
			echo $post_excerpt;
			echo '</div>';
		}
		?>

		<?php
		if ( $config->get( 'show_details' ) && isset( $details_btn ) ) {
			echo $details_btn;
		}
		?>

		<?php
		// TODO: Uncomment on production.
		// echo presscore_post_edit_link();
		?>

	</div>
</div>