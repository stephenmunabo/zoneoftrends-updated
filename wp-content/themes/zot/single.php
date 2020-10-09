<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		
    </div>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<section class="product-det">
  <div class="container">
    <div class="site-title">
      <h2><?php the_title(); ?></h2>
    </div>

    <div class="row prodDetailWrapper">
      <div class="wrapper">
        <div class="row">
          <div class="col-12 center-content">

            <?php the_content(); ?>

          </div>
        </div>

      </div>
    </div>

  </div>
</section>

<?php endwhile; wp_reset_query(); ?>




<div class="wrapper">
    




        
<?php get_footer();  ?>