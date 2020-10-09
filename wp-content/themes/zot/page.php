<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the wordpress construct of pages
 * and that other 'pages' on your wordpress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<section class="pt-100 pb-100">
	<div class="container">
		<div class="site-title">
			<h2><?php the_title(); ?></h2>
		</div>

			<div class="w-100">
				<div class="row">
					<div class="col-12 center-content">

						<?php the_content(); ?>

					</div>
				</div>

			</div>

	</div>
</section>

<?php endwhile; wp_reset_query(); ?>

<?php get_footer();  ?>