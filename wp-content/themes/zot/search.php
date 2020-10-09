<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

<div class="container">
	<div id="content" role="main">


		<?php if ( have_posts() ) : ?>
			<div class="m1-h">
				<h5><?php printf( __( 'Search Results for: %s', 'twentyten' ), '<span>' . get_search_query() . '</span>' ); ?></h5>
			</div>
			
			<ul class="prod-list">
				<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				<li>
					<div class="proWrap">
						<div class="proBox">
							<img src="<?php the_post_thumbnail_url('full'); ?>" alt="">
						</div>
						<div class="proCon">
							<a href="<?php the_permalink(); ?>"><p><?php the_title(); ?></p></a>
						</div>
					</div>
					</li><?php endwhile; ?>

					<?php else : ?>
						<div class="m1-h">
							<h5><?php _e( 'OOPS, Nothing Found', 'twentyten' ); ?></h5>
						</div>
					<?php endif; ?>
					
				</ul>
			</div>
		</div>

		<?php get_footer(); ?>
