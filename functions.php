<?php

// 'post-formats', 'post-thumbnails', 'custom-header', 'custom-background', 'custom-logo', 'menus', 'automatic-feed-links', 'html5', 'title-tag', 'customize-selective-refresh-widgets', 'starter-content', 'responsive-embeds', 'align-wide', 'dark-editor-style', 'disable-custom-colors', 'disable-custom-font-sizes', 'editor-color-palette', 'editor-font-sizes', 'editor-styles', 'wp-block-styles', and 'core-block-patterns'.

if ( ! function_exists( 'elegant_food_theme_setup' ) ) {
	function elegant_food_theme_setup() {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		$args = array(
			'flex-width'    => true,
			'width'         => 1200,
			'flex-height'   => true,
			'height'        => 300,
			'default-image' => get_template_directory_uri() . '/assets/images/header.jpg',
		);
		add_theme_support( 'custom-header', $args );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'custom-logo', array(
			'height'      => 150,
			'width'       => 200,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array( 'site-title', 'site-description' ),
		) );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	}
}

add_action( 'after_setup_theme', 'elegant_food_theme_setup' );


add_action( 'wp_enqueue_scripts', 'elegant_food_wp_enqueue_scripts' );

function elegant_food_wp_enqueue_scripts() {	
	
	wp_enqueue_style( 'eleganttheme-food', get_stylesheet_directory_uri() . '/assets/css/style.css', '', '1.0.99', 'all' );
	
	if ( is_front_page() ) {
		// Bootstrap & Fontawesome css for our tab navigation.
		wp_enqueue_style( 'bootstrap-css', get_stylesheet_directory_uri() . '/assets/css/bootstrap.min.css', '', '1.0.0', 'all' );
		wp_enqueue_style( 'fontawesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css', '', '1.0.0', 'all' );

		// Bring in styles from bootstrap for our tab navigation.
		wp_enqueue_script( 'bootstrap-bundle', get_stylesheet_directory_uri() . '/assets/js/bootstrap.bundle.min.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script( 'eleganttheme-food-add', get_stylesheet_directory_uri() . '/assets/js/food-add.js', array( 'jquery' ), '1.0.3', true );

		wp_localize_script( 'eleganttheme-food-add', 'ajax_object', [ 'ajax_url' => admin_url('admin-ajax.php') ] );
	}

}

add_action( 'wp_ajax_eleganttheme_food_ajax_add_to_cart', 'eleganttheme_food_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_eleganttheme_food_ajax_add_to_cart', 'eleganttheme_food_ajax_add_to_cart' );

function eleganttheme_food_ajax_add_to_cart() {

	$product_id   = absint($_POST['product_id']);
	$variations = $_POST['variations'];
	$quantity     = 1;
	foreach($variations as $variation_id){ 
	if ( WC()->cart->add_to_cart($product_id,$quantity,$variation_id) ) {
		do_action( 'woocommerce_ajax_added_to_cart', $product_id);

		if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
			wc_add_to_cart_message( array( $product_id => $quantity ), true );
		}
	} else {
		$data = array (
			'error' => true,
			'product_url' => get_permalink( $product_id )
		);
	}
	}
//	wp_send_json($product_id);
	
	wp_die();
}