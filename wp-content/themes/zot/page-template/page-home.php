<?php
/**
 * Template Name: Home Template
 */

get_header(); ?>

<ul class="index-slider">
  <?php $count=0; $index_query = new WP_Query(array( 'post_type' => 'slider', 'posts_per_page' => -1, 'order'=>'ASC')); ?>
  <?php while ($index_query->have_posts()) : $index_query->the_post(); $count++; ?>
    <li>
      <div class="mainBanner" style="background-image:url(<?php the_post_thumbnail_url('full'); ?>); ">
        <div class="container">
          <div>
            <h1><?php echo breaklink(get_the_title(), '3') ?></h1>
          </div>
        </div>
      </div>
    </li>
  <?php endwhile; ?>
</ul>

<div class="menu-sec">
 <div class="container text-left">
  <ul class="menuBar menuSlider">
    <?php
    $count=1;
    $terms = get_terms( array(
      'taxonomy' => 'product_cat',
      'hide_empty' => false,
    ) );
    foreach ($terms as $term) {
      $id = $term->term_id;
      $cat_name = $term->name;
      ?>
      <?php if($count <= 4){ ?>
        <li data-targetit="box-<?php echo $id;?>"><a href="#"><?php echo $cat_name;?></a></li>
        <?php $count++; } ?>
      <?php }?>
      <li class="dropdown-nav"><a href="#">More</a>
        <ul class="dropdown">
         <div>
          <ul class="menuBar menuDrp">
            <?php $count=1;
            foreach ($terms as $term) {
              $id = $term->term_id;
              $cat_name = $term->name;
              ?>
              <?php if($count >= 5){ ?>
                <?php if(!($cat_name == "Mens" || $cat_name == "Womens" || $cat_name == "Kids")){ ?>
                 <li data-targetit="box-<?php echo $id;?>"><a href="#"><?php echo $cat_name;?></a></li>
               <?php } ?>
             <?php } $count++; }?>
           </ul>
           <div class="closeBtn">
             <a href="#">Close</a>
           </div>
         </div>
       </ul>
     </li>
   </ul>
   <ul class="menu2">
     <?php foreach ($terms as $term) {
      $id = $term->term_id;
      $cat_name = $term->name;
      ?>
      <?php if($cat_name == "Mens" || $cat_name == "Womens" || $cat_name == "Kids"){ ?>
        <li data-targetit="box-<?php echo $id;?>"><a href="#"><?php echo $cat_name;?></a></li>
      <?php }} ?>
    </ul>
  </div>
</div>
<section class="product-sec">
 <div class="container">
   <?php
   $terms = get_terms( array(
    'taxonomy' => 'product_cat',
    'hide_empty' => false,
  ) );
   foreach ($terms as $term) {
    $id = $term->term_id;
    $cat_name = $term->name;
    ?>
    <ul class="box-<?php echo $id;?> <?php if($id == 23){echo "showfirst";}else {}?> prod-list">
      <?php
      $args = array(
       'post_type' => 'product',
       'product_cat'    => $term->name,
       'posts_per_page' => '-1',
       'order' => 'asc'
     );
     $index_query = new WP_Query($args); ?>
     <?php while ($index_query->have_posts()) : $index_query->the_post(); global $product;?>
       <li class=<?php if($id == 34){echo "dropdown-nav";} else {}?>>
        <div class="proWrap">
          <div class="proBox">
            <?php 
            
            $product = new WC_Product( get_the_ID() );
            $attachment_ids = $product->get_gallery_image_ids();
            
            if ( is_array( $attachment_ids ) && !empty($attachment_ids) ) {
              $first_image_url = wp_get_attachment_url( $attachment_ids[0] );
                // ... then do whatever you need to do
            } // No images found
            else {
                // @TODO
            }
            
            ?>
            <a href="<?php the_permalink(); ?>" class="d-block"><img class="test" data-main="<?php the_post_thumbnail_url('full'); ?>" data-altimg="<?php echo $first_image_url; ?>" src="<?php the_post_thumbnail_url('full'); ?>" alt=""></a>
            <div class="proPrice">
              <h6><?php echo $product->get_price_html(); ?></h6>
              <a href="<?php echo get_site_url(); ?>/cart/?add-to-cart=<?php echo $product->get_ID(); ?>"><i class="fa fa-cart-plus"></i></a>
            </div>
            <ul class="proPlay">
              <?php if( have_rows('story_post') ): ?>
                <li id="<?php echo $product->get_ID(); ?>" class="play-story"><a href="#"><i class="fa fa-play-circle"></i></a></li>
              <?php endif; ?>
              <li><a href="<?php echo get_site_url(); ?>/wishlist/?add_to_wishlist=<?php echo $product->get_ID(); ?>"><i class="fa fa-heart-o"></i></a></li>
            </ul>
          </div>
          <div class="proCon text-left">
            <a id="url-<?php echo $product->get_ID(); ?>" href="<?php the_permalink(); ?>"><p>
              <?php 
              if (strlen($product->get_name()) > 45) {
                echo substr($product->get_name(), 0, 45) . '...';
              }else {
                echo $product->get_name();
              }

              ?>
            </p></a>
          </div>
          <div data-ajax="<?php echo admin_url('admin-ajax.php'); ?>" id="ajax-url"></div>
          <div id="product-<?php echo $product->get_ID(); ?>" data-story="<?php

              // Check rows exists.
          if( have_rows('story_post') ):
                //$post_value = array();
                // Loop through rows.
          while( have_rows('story_post') ) : the_row();

                    // Load sub field value.
          $post_value = get_sub_field('post');
          echo  $post_value . '|';

                // End loop.
          endwhile;

              // No value.
          else :
                // Do something...
          endif;

          ?>"
          ></div>
        </div>
      </li>
      

    <?php endwhile; wp_reset_query(); ?>
  </ul>
<?php } ?>
<a href="#" class="loadMore">Load More</a>
</div>
</section>

<?php get_footer(); ?>