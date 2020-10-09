<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<header>
 <div class="header-top">
  <div class="container">
   <p>GET 30% OFF ENTIRE PURCHASE</p>
   <p class="mr-0">Use code: <span>SUMMER</span></p>
 </div>
 <a href="#" id="closeMessage">x</a>
</div>
<div class="main-header subHeader">
  <div class="container">
   <div class="menu-Bar">
    <span></span>
    <span></span>
    <span></span>
  </div>
  <div class="menuDefault">
    <div class="row align-items-center">
     <div class="col-md-7 text-right">
      <div class="menuWrap">
       <div class="searchWrap text-left">
        <a href="<?php echo get_site_url();?>" class="backBtn">Back</a>
      </div>
    </div>
  </div>
  <div class="col-md-3">
  <ul class="cart-list">
                  <?php $wishlist_count = YITH_WCWL()->count_products(); ?>
                  <li><a href="<?php echo get_site_url();?>/wishlist" class="heart-ico"><img src="<?php bloginfo('template_directory'); ?>/images/icons/wish.png" alt=""><span><?php echo $wishlist_count; ?></span></a></li>
                  <li><a href="<?php echo get_site_url();?>/cart" class="bag-ico"><img src="<?php bloginfo('template_directory'); ?>/images/icons/basket.png" alt=""><span><?php global $woocommerce;
                  echo $woocommerce->cart->cart_contents_count; ?></span></a></li>
                   <?php if(is_user_logged_in()): ?>
                  <li><a href="<?php echo get_site_url();?>/my-account" class="bag-ico"><img src="<?php bloginfo('template_directory'); ?>/images/icons/profile.png" alt=""></a></li>
                  <?php else: ?>
                  <li><a data-toggle="modal" data-target="#loginModal" href="#!"><img src="<?php bloginfo('template_directory'); ?>/images/icons/profile.png" alt=""></a></li>
                  <?php endif; ?>
                </ul>
 </div>
 <div class="col-md-2 text-right">
  <a href="<?php echo get_site_url();?>" class="logo"><?php echo $options['logo'];?></a>
</div>
</div>
</div>

<div class="menuResp">
  <a href="<?php echo get_site_url();?>" class="backBtn">Back</a>
  <div>
   <div class="menuWrap">
    <div class="searchWrap">
     <form action="">
      <input type="text" placeholder="Search for something">
      <button><i class="fa fa-search"></i></button>
    </form>
  </div>
  <ul class="cart-list">
   <?php $wishlist_count = YITH_WCWL()->count_products(); ?>
   <li><a href="<?php echo get_site_url();?>/wishlist" class="heart-ico"><i class="fa fa-heart-o"></i><span><?php echo $wishlist_count; ?></span></a></li>
   <li><a href="<?php echo get_site_url();?>/cart" class="bag-ico"><i class="fa fa-shopping-bag"></i><span><?php global $woocommerce;
   echo $woocommerce->cart->cart_contents_count; ?></span></a></li>
   <li><a href="<?php echo get_site_url();?>/my-account" class="bag-ico"><i class="fa fa-user-circle-o"></i></a></li>
 </ul>
</div>
</div>
</div>
</div>
</div>
</header>

<section class="product-det">
    <div class="container">
  <div class="row">
   <div class="col-md-6">
    <div>
      <?php
      global $product;
      $attachment_ids = $product->get_gallery_image_ids();
      foreach( $attachment_ids as $attachment_id ) {
        $image_link = wp_get_attachment_url( $attachment_id ); ?>
        <img src="<?php echo $image_link; ?>" alt="" class="w-100">
      <?php } ?>

      <div class="instaPcs">
        <h6>STYLED ON INSTAGRAM:</h6>
        <ul class="instaList">
          <?php
          global $product;
          $attachment_ids = $product->get_gallery_image_ids();
          foreach( $attachment_ids as $attachment_id ) {
            $image_link = wp_get_attachment_url( $attachment_id ); ?>
            <li><img src="<?php echo $image_link; ?>" alt=""></li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
  <div class="col-md-6">
   <div class="prod-desc">
    <h4><?php the_title(); ?></h4>
    <?php echo $product->get_price_html(); ?>
    <div class="size">
      <?php custom_variation_form(get_the_ID()); ?>
    </div>
    <div class="size">
     <p>Security:</p>
     <img src="<?php bloginfo('template_directory'); ?>/images/secure.png" alt="">
   </div>
   <div class="desc">
     <h6>Description:</h6>
     <p><?php the_excerpt(); ?></p>
   </div>
 </div>
</div>
</div>
</div>
</section>

<section class="product-sec">
 <div class="container">
  <h6 class="py-4">SIMILAR PRODUCTS</h6>
  <ul class="prod-list">
    <?php $count=0; $index_query = new WP_Query(array( 'post_type' => 'product', 'posts_per_page' => 8, 'order'=>'ASC')); ?>
    <?php while ($index_query->have_posts()) : $index_query->the_post(); $count++; ?>
     <li>
      <div class="proWrap">
       <div class="proBox">
        <a href="<?php the_permalink(); ?>" class="d-block"><img src="<?php the_post_thumbnail_url('full'); ?>" alt=""></a>
        <div class="proPrice">
         <h6><?php echo $product->get_price_html(); ?></h6>
         <a href="<?php echo get_site_url(); ?>/cart/?add-to-cart=<?php echo $product->get_ID(); ?>"><i class="fa fa-cart-plus"></i></a>
       </div>
       <ul class="proPlay">
         <li><a href="#"><i class="fa fa-play-circle"></i></a></li>
         <li><a href="<?php echo get_site_url(); ?>/wishlist/?add_to_wishlist=<?php echo $product->get_ID(); ?>"><i class="fa fa-heart-o"></i></a></li>
       </ul>
     </div>
     <div class="proCon text-left">
      <a href="<?php the_permalink(); ?>"><p><?php the_title(); ?></p></a>
    </div>
  </div>
</li>
<?php endwhile; ?>
</ul>
</div>
</section>

<?php endwhile; wp_reset_query(); ?>
<?php get_footer();  ?>