<?php
/**
 * Category Archive pages.
 */

get_header(); ?>


<strong>
	<?php 
/*
$post_obj = $wp_query->get_queried_object();
$post_ID = $post_obj->ID;
$post_title = $post_obj->post_title;
$post_name = $post_obj->post_name;	
*/

?>

<?php 



//single_cat_title(''); 
//single_cat_title('You are reading about ').'<br />'; 
//echo $current_category = single_cat_title("", false).'<br />'; ;
//echo getSlug();
?>

 
    </strong>
		
<div class="content">

			<div class="cat-bx"> 
                <?php 
					$category = new WP_Query( 'category_name='.getSlug().'&showposts=3' ); 
				?>  
			    <?php while( $category->have_posts() ) : $category->the_post();  ?>
				
                <div class="cat-bx-rw">
                    <h2><?php multiColor(get_the_title(),1); ?></h2>
                    <img src="<?php echo catch_that_image(); ?>"  />
                    <?php //echo catch_image(); ?>
                    <p>
                    	<?php 
							$content = post_content();
							echo showBrief($content,120);
						?>
                    </p>
                    <a href="<?php the_permalink(); ?>">Read More</a>
                </div>
                
				<?php endwhile; wp_reset_postdata(); ?>
            </div>

</div><!-- #content -->
		

<?php get_sidebar(); ?>
<?php get_footer(); ?>
