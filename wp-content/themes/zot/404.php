<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>
<meta http-equiv="refresh" content="1;url=<?php echo get_site_url(); ?>" />
<section class="overly prodDetailSec">
	<div class="container">
		<div class="m1-h text-center">
			<h5>NOT FOUND</h5>
		</div>

		<div class="row prodDetailWrapper">

<div class="content-inner">
	<div class="wrapper">
		<div class="content-main">
        
    		<div id="post-0" class="post error404 not-found">
			<!-- 	<h1 class="entry-title"><?php _e( 'Not Found', 'twentyten' ); ?></h1> -->
				<br />
                <div class="entry-content">
					<!-- <p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'twentyten' ); ?></p> -->
					<?php //get_search_form(); ?>
				</div><!-- .entry-content -->
			</div><!-- #post-0 -->
			<script type="text/javascript">
                // focus on search field after it has loaded
                document.getElementById('s') && document.getElementById('s').focus();
            </script>
    	</div>
	</div>
</div>
</div>
</div>
</section>

<?php get_footer(); ?>