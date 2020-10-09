<?php 
//Getting Theme Templete

require_once ( get_stylesheet_directory() . '/theme-options.php' );

add_theme_support('post-thumbnails');

add_theme_support( 'menus' );

/**
 * Register sidebars.
 *
 * Registers our main widget area and the front page widget areas.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'twentytwelve' ),
		'id' => 'sidebar-1',
		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'First Front Page Widget Area', 'twentytwelve' ),
		'id' => 'sidebar-2',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Second Front Page Widget Area', 'twentytwelve' ),
		'id' => 'sidebar-3',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'twentytwelve_widgets_init' );

//  show Brief 	 function  show editor content 

function showBrief($str, $length) {
	//$str = strip_tags($str);
	$str = preg_replace("/\< *[img][^\>]*[.]*\>/i","",$str,1);
	$str = explode(" ", $str);
	return implode(" " , array_slice($str, 0, $length));
}

  // Two Colors 

function multiColor($title,$position){
	$title = explode(" ",$title );
	$count = count($title);
	for($i=0 ; $i < $count ; $i++){
		if($i == $position)echo '<span>';
		echo $title[$i].' ';
		if($i == ($count-1))echo '</span>';
	}
}



function breaklink($title,$position){
	$title = explode(" ",$title );
	$count = count($title);
	for($i=0 ; $i < $count ; $i++){
		if($i == $position)echo '<br />';
		echo $title[$i].' ';
	}
}

add_action('init', 'slider');
function slider() {
	$labels = array(
		'name' => _x('Slider', 'post type general name'),
		'singular_name' => _x('Slider', 'post type singular name'),
		'add_new' => _x('Add New', 'Slider item'),
		'add_new_item' => __('Add New Slider Item'),
		'edit_item' => __('Edit Slider Item'),
		'new_item' => __('New Slider Item'),
		'view_item' => __('View Slider Item'),
		'search_items' => __('Search Portfolio'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'), 
		'parent_item_colon' => ''
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
	 	 //'supports' => array('title','editor','author','thumbnail','post-thumbnails','excerpts','trackbacks','custom-fields','comments','revisions','page-attributes')
		'supports' => array('title','editor','thumbnail')
	); 
	
	register_post_type( 'slider' , $args );
}

function hide_admin_bar(){ return false; }
add_filter( 'show_admin_bar', 'hide_admin_bar' );

add_filter( 'woocommerce_return_to_shop_redirect', 'bbloomer_change_return_shop_url' );
 
function bbloomer_change_return_shop_url() {
return home_url();
}

/*####################################################*/
/*Woo Commerce Suppoprt Function (Support File)*/
/*####################################################*/

function aventurine_child_wc_support() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'aventurine_child_wc_support' );

/*####################################################*/
/*####################################################*/

/*####################################################*/
/*Defining how much elements display in a Row*/
/*####################################################*/
/*
 * Change number or products per row to 4
 */
add_filter('loop_shop_columns', 'loop_rows');
if (!function_exists('loop_rows')) {
	function loop_rows() {
    return 3; // 3 products per row
}
}

/*####################################################*/
/*Removing Default Style from SHOP Page*/
/*####################################################*/

/*####################################################*/
		//REMOVE SHOP PAGE ADD TO CART BUTTON
/*####################################################*/
function remove_loop_button(){
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
}
add_action('init','remove_loop_button');
/*####################################################*/

/*####################################################*/
				//REMOVE SHOP PAGE IMAGE 
/*####################################################*/
function remove_woocommerce_actions() {
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
}
add_action( 'after_setup_theme', 'remove_woocommerce_actions' );
/*####################################################*/

/*####################################################*/
				//REMOVE SHOP PAGE TITLE
/*####################################################*/
remove_action('woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title',10);

/*For Price change CSS to Display:none;*/

function skyverge_shop_display_skus() {

	global $product;
	$link = $product->get_permalink();

	?>
	<!-- Data of products page -->

	<a href="<?php the_permalink();?>" class="plida-area"> 
		<div class="partImg lida">
			<img src="<?php the_post_thumbnail_url('full'); ?>" alt="<?php echo $product->get_name(); ?>">
		</div>
		<div class="partname">
			<h5><?php echo $product->get_name(); ?></h5>
			<p><?php  ?><?php $string = get_field('excerpt');
			
			    if($string[0]=='$'){
			        $str = ltrim($string, '$'); 

			        echo get_woocommerce_currency_symbol() . $product->get_price();
			    }else{
			        
			        echo $string;
			        
			    }
			?></p>
		</div>
	</a>

	<?php 
}
add_action( 'woocommerce_after_shop_loop_item', 'skyverge_shop_display_skus', 11 );

/* CUSTOM WOOCOMMERCE FUNCTION START*/



				function custom_variation_form($id) {

					$product = wc_get_product( $id );

					if( $product->is_type( 'variable' )) {

						wp_enqueue_script('wc-add-to-cart-variation');

						$attribute_keys = array_keys( $product->get_variation_attributes() );

						?>

						<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->id ); ?>" data-product_variations="<?php echo htmlspecialchars( json_encode( $product->get_available_variations() ) ) ?>">
							<?php do_action( 'woocommerce_before_variations_form' ); ?>

							<?php if ( empty( $product->get_available_variations() ) && false !== $product->get_available_variations() ) : ?>
							<p class="stock out-of-stock">
								<?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?>
							</p>
							<?php else : ?>
								<table class="variations" cellspacing="0">
									<tbody>
										<?php foreach ( $product->get_variation_attributes() as $attribute_name => $options ) : ?>
											<tr>
												<!-- <td class="label"><label for="<?php echo sanitize_title( $attribute_name ); ?>"><?php echo wc_attribute_label( $attribute_name ); ?></label></td> -->
												<td class="value">
													<label><?php echo ucfirst(str_replace("pa_" , "" , $attribute_name)); ?></label>
													<?php
													$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) : $product->get_variation_default_attribute( $attribute_name );
													wc_dropdown_variation_attribute_options( array( 'options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected ) );
													?>
												</td>
											</tr>
										<?php endforeach;?>
									</tbody>
								</table>

								<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

								<div class="single_variation_wrap">
									<?php
          /**
           * woocommerce_before_single_variation Hook.
           */
          do_action( 'woocommerce_before_single_variation' );

          /**
           * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
           * @since 2.4.0
           * @hooked woocommerce_single_variation - 10 Empty div for variation data.
           * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
           */
          do_action( 'woocommerce_single_variation' );

          /**
           * woocommerce_after_single_variation Hook.
           */
          do_action( 'woocommerce_after_single_variation' );
          ?>
      </div>

      <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
      
  <?php endif; ?>

  <?php do_action( 'woocommerce_after_variations_form' ); ?>

</form>

<?php } else { ?>

	<form class="cart" action="<?php echo esc_url( get_permalink() ); ?>" method="post" enctype='multipart/form-data'>

		<?php

		woocommerce_quantity_input( array(
			'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
			'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
			'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : $product->get_min_purchase_quantity(),
		) );


		?>
		<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

	</form>

<?php } 

}


add_filter('woocommerce_form_field_args',  'wc_form_field_args',10,3);
function wc_form_field_args($args, $key, $value) {
  $args['input_class'] = array( 'form-control' );
  return $args;
} 

add_filter( 'woocommerce_variable_sale_price_html', 'wc_wc20_variation_price_format', 10, 2 );
 add_filter( 'woocommerce_variable_price_html', 'wc_wc20_variation_price_format', 10, 2 );
 function wc_wc20_variation_price_format( $price, $product ) {
 // Main Price
 $prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
 $price = $prices[0] !== $prices[1] ? sprintf( __( 'Price: %1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );
 // Sale Price
 $prices = array( $product->get_variation_regular_price( 'min', true ), $product->get_variation_regular_price( 'max', true ) );
 sort( $prices );
 $saleprice = $prices[0] !== $prices[1] ? sprintf( __( 'Previous Price: %1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );
 if ( $price !== $saleprice ) {
   $price = '<ins>' . $price . '</ins> <del>' . $saleprice . '</del>';
 }
   return $price;
 }




 function ajax_login_init(){

    wp_register_script('ajax-login-script', get_template_directory_uri() . '/ajax-login-script.js', array('jquery') ); 
    wp_enqueue_script('ajax-login-script');

    wp_localize_script( 'ajax-login-script', 'ajax_login_object', array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'redirecturl' => home_url(),
        'loadingmessage' => __('Sending user info, please wait...')
    ));

    // Enable the user with no privileges to run ajax_login() in AJAX
    add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
}

// Execute the action only if the user isn't logged in
if (!is_user_logged_in()) {
    add_action('init', 'ajax_login_init');
}



//Login auth
function ajax_login(){

    // First check the nonce, if it fails the function will break
    check_ajax_referer( 'ajax-login-nonce', 'security' );

    // Nonce is checked, get the POST data and sign user on
    $info = array();
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
    $info['remember'] = true;

    $user_signon = wp_signon( $info, false );
    if ( is_wp_error($user_signon) ){
        echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
    } else {
        echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...')));
    }

    die();
}


add_action( 'wp_ajax_f711_get_post_content', 'f711_get_post_content_callback' );
// If you want not logged in users to be allowed to use this function as well, register it again with this function:
add_action( 'wp_ajax_nopriv_f711_get_post_content', 'f711_get_post_content_callback' );

function f711_get_post_content_callback() {

    // retrieve post_id, and sanitize it to enhance security
    $post_id = intval($_POST['post_id'] );

    // Check if the input was a valid integer
    if ( $post_id == 0 ) {

        $response['error'] = 'true';
        $response['result'] = 'Invalid Input';

    } else {

        // get the post
        $thispost = get_post( $post_id );

        // check if post exists
        if ( !is_object( $thispost ) ) {

            $response['error'] = 'true';
            $response['result'] =  'There is no post with the ID ' . $post_id;

        } else {

            $response['error'] = 'false';
            $response['result'] = wpautop( $thispost->post_content );

        }

    }

    wp_send_json( $response );

}


