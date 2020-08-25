<?php
/*
Plugin Name: kwebsites
Description: Plugin for the Koded Web Test
Author: Angel Utset
*/

/**
 * Enqueue scripts and styles.
 */
function kwebsites_scripts() {

    $url = array(
        'ajax_url' => admin_url( 'admin-ajax.php' ));

    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), null, false);
    wp_enqueue_style('bootstrap-style', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css'); // Registering Bootstrap 4
    wp_enqueue_script( 'bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js');
    wp_enqueue_style( 'Font_Awesome', 'https://use.fontawesome.com/releases/v5.11.2/css/all.css');
    wp_enqueue_script( 'kwform', plugin_dir_url( __FILE__ ) . "inc/kwforms.js");
    wp_localize_script( 'kwform', 'kw', $url );
}
add_action( 'wp_enqueue_scripts', 'kwebsites_scripts' );


// Register KWebsitem Post Type
function kwebsite_post_type() {

    $labels = array(
       'name'                  => __( 'Kwebsites', 'Post Type General Name', 'text_domain' ),
       'singular_name'         => __( 'kwebsite', 'Post Type Singular Name', 'text_domain' ),
       'menu_name'             => __( 'KWebsites', 'text_domain' ),
       'name_admin_bar'        => __( 'KWebsite', 'text_domain' ),
       //'edit_item'             => __( 'Edit KWebsite', 'textdomain' ),
       'view_item'             => __( 'View KWebsite', 'textdomain' ),
       'all_items'             => __( 'All KWebsites', 'text_domain' )
   );

   $args = array(
       'label'                 => __( 'Post Type', 'text_domain' ),
       'description'           => __( 'Post Type Description', 'text_domain' ),
       'labels'                => $labels,
       'supports'              => array('title'),
       'hierarchical'          => false,
       'public'                => true,
       'show_ui'               => true,
       'show_in_menu'          => true,
       'menu_position'         => 5,
       'show_in_admin_bar'     => true,
       'show_in_nav_menus'     => true,
       'can_export'            => false,
       'has_archive'           => true,
       'exclude_from_search'   => true,
       'publicly_queryable'    => false,
       'capability_type'       => 'post',
       'capabilities' => array(
        'create_posts' => false 
    ),
       'map_meta_cap' => true,
   );
   register_post_type( 'kwebsite', $args ); 

}
add_action( 'init', 'kwebsite_post_type', 0 );

function kwform() {
    $file = 'inc/form.php';
      ob_start(); // turn on output buffering
      include_once($file);
      $res = ob_get_contents(); 
      ob_end_clean(); 	  
    return $res;
}
add_shortcode('kwform', 'kwform');

add_action("wp_ajax_kwebsitecp" , "kwebsitecp");
add_action("wp_ajax_nopriv_kwebsitecp" , "kwebsitecp");

function kwebsitecp() {
    global $wpdb; 
    

    $kwname = $_POST['kname'];
    $kwurl = $_POST['kurl'];

    // Creates the custom post
    $nkw = array(
        'post_title'    => $kwname,
        'post_content'  => '',
        'post_status'   => 'publish',
        'post_type' => 'kwebsite' );
    if (get_page_by_title($kwname, '', 'kwebsite') === null) // Does not exist a KWebsite custom post with the same name
    $nkwid = wp_insert_post($nkw, true); // Kwebsite created

    //Adding the post metadata
    add_post_meta($nkwid, 'Name', $kwname);
    add_post_meta($nkwid, 'URL', $kwurl);
    //add_post_meta($nkwid, 'URL Source Code', $kwurldat);
    
    
    wp_die();
}

//Creating the Backend process
// Kwebsites menu
function kw_create_menu(){
       
  // add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
  add_menu_page(  'KwebsitesW', 'Kwebsites_List', 'manage_options', 'kw/backend-admin.php', 'kw_show_websites', 'dashicons-welcome-widgets-menus', 6);
}

add_action( 'admin_menu', 'kw_create_menu' );

//Removing the Edit Metaboxes
add_action( 'admin_menu' , 'kw_remove_post_custom_fields' );

function kw_remove_post_custom_fields() {
    remove_meta_box( 'slugdiv', 'kwebsite', 'normal' );	// Slug meta box 
    remove_meta_box( 'submitdiv','kwebsite','normal' ); // Formats Metabox
    //remove_meta_box( 'revisionsdiv','kwebsite','normal' ); // Formats Metabox	
}

//Kwebsites backend page
function kw_show_websites() {
    ?>
    <div class="wrap">
    <h2><span class="dashicons dashicons-list-view"></span> KWebsites - Showing the data retrieved</h2>
    <div class="notice notice-info is-dismissible">
    <h4>I show below the KWebsites retrieved (i.e. Names and URLs). </h4></div>
    <form method="POST" action="">
        <table class="widefat">
        <col style="width:10%">
	    <col style="width:30%">
	    <col style="width:60%">
        <thead>
         <tr>
            <th><b>ID</b></th>
            <th><b>Name</b></th>
            <th><b>URL</b></th>
        </tr>
        </thead>
        <tbody>
        <tr>
<?php
include_once ('inc/kwtable.php');
?>
        </tbody>
        </table>          

   </div>
<?php
        }
//Metaboxes
function kw_createMetaboxes() {
    add_meta_box('kwurl', 
        'Visitor URL',
        'kwurl_mb',
        'kwebsite');
    add_meta_box('kwurl_code', 
        'Source Code of Visitor URL',
        'kwurlc_mb',
        'kwebsite');
}
add_action('add_meta_boxes', 'kw_createMetaboxes');

function kwurl_mb() {   
    $kwurl = get_post_meta(get_the_ID(), 'URL', true);
    echo 'Visitor URL: '.$kwurl;
}

function kwurlc_mb() {
    include_once 'inc/curlexec.php';
    $kwurl = get_post_meta(get_the_ID(), 'URL', true);
    //Obtaining the URL content
    $kwg = wp_remote_get($kwurl);
    $kwudat = new curlexec($kwurl);
    $kwurldat = $kwudat->get_res(); 
    //Encoding and showing
    $html_encoded = htmlentities($kwurldat);
    echo $html_encoded; 
}