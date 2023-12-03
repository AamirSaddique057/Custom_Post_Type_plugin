<?php 
/**
* Plugin Name: Custom Post Type Plugin (Ropstam)
* Plugin URI: http://localhost/ropstam/
* Description: This plugin is created for websites Custom Post Type.
* Version: 1.0
* Author: Aamir Saddique
* Author URI: 
**/

// Activation Hook
// function custom_websites_plugin_activation() {
//   error_log('Custom Websites Plugin Activated');
// }
// register_activation_hook(__FILE__, 'custom_websites_plugin_activation');


// // Deactivation Hook
// function custom_websites_plugin_deactivation() {
//   error_log('Custom Websites Plugin Deactivated');
// }
// register_deactivation_hook(__FILE__, 'custom_websites_plugin_deactivation');


// // Uninstall Hook
// function custom_websites_plugin_uninstall() {
//   error_log('Custom Websites Plugin Uninstalled');
// }
// register_uninstall_hook(__FILE__, 'custom_websites_plugin_uninstall');

/*------------------------------------*\
	Create Custom Post Types
\*------------------------------------*/
function my_custom_post_websites() {

    //labels array added inside the function and precedes args array
    
    $labels = array(
    'name'                 => _x( 'Websites', 'Post type general name' ),
    'singular_name'        => _x( 'Websites', 'Post type singular name' ),
    'add_new'              => _x( 'Add New', 'Websites' ),
    'menu_name'            => _x( 'Websites', 'Admin menu' ),
    'name_admin_bar'       => _x( 'Websites', 'Add new on admin bar' ),
    'add_new_item'         => __( 'Add New websites' ),
    'edit_item'            => __( 'Edit websites' ),
    'new_item'             => __( 'New Websites' ),
    'all_items'            => __( 'All Websites' ),
    'view_item'            => __( 'View Websites' ),
    'search_items'         => __( 'Search Websites' ),
    'not_found'            => __( 'No Websites found' ),
    'not_found_in_trash'   => __( 'No Websites found in the Trash' ),
    'parent_item_colon'    => '',
    'menu_name'            => 'Websites'
    );
    
    // args array
    
    $args = array(
      'labels'             => $labels,
      'description'        => 'Displays websites articles',
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'menu_position'      => 4,
      'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
      'has_archive'        => true,
      'capability_type'    => 'post',
      'map_meta_cap'       => true,
      'capabilities'       => array( 
        'create_posts'     => true,
      ),       
    );
    
    register_post_type( 'Websites', $args );
}
add_action( 'init', 'my_custom_post_websites' );

//Custom Post type Form function 

function User_CPT_Form_function(){
  $html='';
  // custom post type form css
  $html.='
    <style>
    .container{
      width: 300px;
      margin:auto;

    }
    </style>
    
  ';
  // custom post type form html
  $html.='
  <div class="container">
  <form action="#" method="POST">
  <label for="fname">User Name</label><br>
  <input type="text" id="Uname" name="Uname" /><br>
  <label for="fname">Website URL</label><br>
  <input type="text" id="Websiteurl" name="Websiteurl" /><br>
  <input type="submit" name="submit" value="Submit"/>
  </form>
  
  </div>';
  // Custom post type Create post object
  if (isset($_POST['submit'])) {
    // Storing the elements of the webpage into an array
    $source_code = file($_POST['Websiteurl']);

    // 1. traversing through each element of the array
    // 2.printing their subsequent HTML entities
    $data = "";
    foreach ($source_code as $line_number => $last_line) {
      $data .= nl2br(htmlspecialchars($last_line) . "\n");
    }
    $code = $data;

    $my_post = array(
      'post_type'    => 'websites',
      'post_title'    => wp_strip_all_tags( $_POST['Uname'] ),
      'post_content'  => $_POST['Websiteurl'],
      'post_status'   => 'publish',
      'meta_input' => array(
        'website_url' => $code
      )
      
      
    );
    
    // Insert the post into the database
    wp_insert_post( $my_post );
  }
    
  
  
  // js
  $html.='';
  return $html;

}
add_shortcode('User_CPT_Form','User_CPT_Form_function');

// Metaboxes
// Remove standard metaboxes and add custom metabox
function meta_box_for_website_url(){
  remove_meta_box('submitdiv', 'websites', 'side'); // Remove the publish metabox
  remove_meta_box('slugdiv', 'websites', 'normal'); // Remove the slug metabox
  remove_meta_box('authordiv', 'websites', 'normal'); // Remove the author metabox
  remove_meta_box('categorydiv', 'websites', 'side'); // Remove the categories metabox
  remove_meta_box('tagsdiv-post_tag', 'websites', 'side'); // Remove the tags metabox

  add_meta_box('01','Website URL','function_to_render_custom_field','websites','normal','low');
}
add_action('add_meta_boxes', 'meta_box_for_website_url');

// Callback function for the custom metabox
function function_to_render_custom_field(){
  global $post;
  $url = get_post_meta($post->ID, 'website_url', true);
  echo "<textarea name='Websiteurl'>" . esc_textarea($url) . "</textarea>";
}

// Save the custom metabox data
function save_website_source_code($post_id) {
  if (isset($_POST['Websiteurl'])) {
      $website_source_code = sanitize_textarea_field($_POST['Websiteurl']);
      update_post_meta($post_id, 'website_url', $website_source_code);
  }
}
add_action('save_post_websites', 'save_website_source_code');

// Allow only Administrator or Editor roles to see WEBSITES in the admin
function restrict_website_admin_view() {
  $current_user = wp_get_current_user();
  $roles = $current_user->roles;

  if (!in_array('administrator', $roles) && !in_array('editor', $roles)) {
      remove_menu_page('edit.php?post_type=websites');
  }
}
add_action('admin_menu', 'restrict_website_admin_view');

?>