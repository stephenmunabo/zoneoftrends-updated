<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *

 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>


<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
  <title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
  <link href="<?php bloginfo('template_directory'); ?>/images/favicon.png" rel="shortcut icon" type="image/x-icon" />
  <link rel="profile" href="http://gmpg.org/xfn/11" />
  <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

  <link rel="stylesheet" herf="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" >
  <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/layout.css">
  <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/style.css">


  <?php
  /* We add some JavaScript to pages with the comment form
   * to support sites with threaded comments (when in use).
   */
  if ( is_singular() && get_option( 'thread_comments' ) )
    wp_enqueue_script( 'comment-reply' );
  global $options;global $logo;global $copyrite;
  $options = get_option('cOptn');
  $logo = $options['logo'];
  $copyrite = $options['copyright'];
  $size = 300;
  $options['logo'] = wp_get_attachment_image($logo, array($size, $size), false);
  $att_img = wp_get_attachment_image($logo, array($size, $size), false); 
  $logoSrc = wp_get_attachment_url($logo);
  $att_src_thumb = wp_get_attachment_image_src($logo, array($size, $size), false);

  /* Always have wp_head() just before the closing </head>
   * tag of your theme, or you will break many plugins, which
   * generally use this hook to add elements to <head> such
   * as styles, scripts, and meta tags.
   */
  wp_head(); ?>

</head>

<body <?php body_class(); ?>>
  
  
  <?php if(!is_single()){ ?>
    <header>
      <div class="header-top">
        <div class="container">
          <p>GET 30% OFF ENTIRE PURCHASE</p>
          <p class="mr-0">Use code: <span>SUMMER</span></p>
        </div>
        <a href="#" id="closeMessage">x</a>
      </div>
      <div class="main-header">
        <div class="container">
          <div class="menu-Bar">
            <span></span>
            <span></span>
            <span></span>
          </div>
          <div class="menuDefault">
            <div class="row align-items-center">
                <div class="col-md-1 text-left">
                    <a href="<?php echo get_site_url();?>" class="logo"><?php echo $options['logo'];?></a>
                  </div>
              <div class="col-md-7 text-right">
                <div class="menuWrap">
                  <div class="searchWrap">
                    <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
                      <input type="text" name="s" id="search" value="<?php the_search_query(); ?>" placeholder="Search for something" />
                    </form>
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
              
            </div>
          </div>

          <div class="menuResp">
            <a href="<?php echo get_site_url();?>" class="logo"><?php echo $options['logo'];?></a>
            <div>
             <div class="menuWrap">
              <div class="searchWrap">
               <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
                <input type="text" name="s" id="search" value="<?php the_search_query(); ?>" placeholder="Search for something" />
              </form>
            </div>
            <ul class="cart-list">
             <?php $wishlist_count = YITH_WCWL()->count_products(); ?>
             <li><a href="<?php echo get_site_url();?>/wishlist" class="heart-ico"><i class="fa fa-heart-o"></i><span><?php echo $wishlist_count; ?></span></a></li>
             <li><a href="<?php echo get_site_url();?>/cart" class="bag-ico"><i class="fa fa-shopping-bag"></i><span><?php global $woocommerce;
             echo $woocommerce->cart->cart_contents_count; ?></span></a></li>
             <?php if(is_user_logged_in()): ?>
             <li><a href="<?php echo get_site_url();?>/my-account" class="bag-ico"><img src="<?php bloginfo('template_directory'); ?>/images/icons/profile.png" alt=""></a></li>
             <?php else: ?>
             <li><a data-toggle="modal" data-target="#loginModal" href="#!"><img src="<?php bloginfo('template_directory'); ?>/images/icons/profile.png" alt=""></a></li>
             <?php endif; ?>
           </ul>
         </div>
       </div>
     </div>
   </div>
 </div>
</header>
<?php } ?>

<?php if(is_shop()){?>
<div class="row">
<div class="container">
<?php wp_nav_menu(array('container' => false, 'menu_class' => 'dropdown-nav', 'theme_location' => 'primary')) ?>
<?php }?>