<?php
/*
 * Plugin Name: Woo AJAX Search
 * Plugin URI: http://renoyes.com/
 * Description: WooCommerce Products AJAX Searching Plugins
 * Version: 1.0
 * Author: anik4e
 * Author URI: http://renoyes.com/
 * License: GPL2+
 * Domain Path: /languages/
 * Text Domain: woo-ajax-search
*/
// Define 
if ( !defined( 'WOOSEARCH' ) )
	define( 'WOOSEARCH', dirname( __FILE__ ) . '/' );

if ( !defined( 'WOOSEARCH_URL' ) )
	define( 'WOOSEARCH_URL', trailingslashit( plugins_url( '' , __FILE__ ) ) );

// Text Domain Add
function woosearch_text_domain() {
	load_plugin_textdomain( 'woo-ajax-search', WOOSEARCH . 'languages', basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'woosearch_text_domain' );

// Add admin assets
function woosearch_add_assets(){
	//CSS
	wp_enqueue_style( 'woosearch-style',  WOOSEARCH_URL.'assets/css/woosearch-style.css', false );
	//JS
	wp_enqueue_script( 'woosearch-js',  WOOSEARCH_URL.'assets/js/woosearch-js.js', array('jquery') );
	wp_localize_script( 'woosearch-js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'wp_enqueue_scripts', 'woosearch_add_assets' );

// Add Menus Settings
require_once( WOOSEARCH . 'includes/settings.php' );

// Add Shortcode
require_once( WOOSEARCH . 'includes/shortcode.php' );
require_once( WOOSEARCH . 'includes/widgets.php' );

// Return Only Data
add_action("wp_ajax_woosearch_search", "woosearch_ajax_data_search_return");
add_action("wp_ajax_nopriv_woosearch_search", "woosearch_ajax_data_search_return");

function woosearch_ajax_data_search_return(){

    $output = '';
    $data_view = '';
    $args = array();

    if(isset($_POST['raw_data'])){
        $args = array(
                    's'                 => $_POST['raw_data'],
                    'post_type'         => 'product',
            );
        if(isset($_POST['category'])){
            if($_POST['category']!='' && $_POST['category']!='all'){
                $args['tax_query']  =   array( 
                                            array(
                                                'taxonomy' => 'product_cat',
                                                'field'    => 'slug',
                                                'terms'    => $_POST['category'],
                                            )
                                        );
            }
        }
        if(isset($_POST['number'])){
             $args['posts_per_page'] = $_POST['number'];
        }else{
            $args['posts_per_page'] = 10;
        }
        $data_view = $_POST['raw_data'];
    }

    $search_data = new WP_Query($args);

    if($search_data->have_posts()):
        $output .= '<ul>';
        while ($search_data->have_posts()): $search_data->the_post();
            $the_title = str_ireplace( $data_view ,"<span>".$data_view."</span>", get_the_title() );
            $output .= '<li><a href="'.get_permalink().'"> '.$the_title.'</a></li>';
        endwhile;
        $output .= '</ul>';
        wp_reset_postdata();
    endif;

    wp_die($output);
}
