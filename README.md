# Custom_Post_Type_plugin: ==========
This plugin is created for websites Custom Post Type.(Ropstam)

# Plugin Header Comments: ==========
  
* Plugin Name: Custom Post Type Plugin (Ropstam)
* Plugin URI: http://localhost/ropstam/
* Description: This plugin is created for websites Custom Post Type.
* Version: 1.0
* Author: Aamir Saddique
* Author URI: 



# Metaboxes
/***
metaboxes are custom content blocks that can be added to the post edit screen. They allow you to add additional fields or content to your posts or custom post types. In your case, you wanted to add a metabox for the website source code
***/
  
# Allow only Administrator or Editor roles to see WEBSITES (custom post type) in the admin

/***
includes logic to restrict access to the 'Websites' custom post type in the admin menu based on user roles. Specifically, the code checks if the current user is either an Administrator or an Editor. If not, it removes the menu page for the 'Websites' custom post type.
***/

  function restrict_website_admin_view() 
  {
    $current_user = wp_get_current_user();
    $roles = $current_user->roles;
    if (!in_array('administrator', $roles) && !in_array('editor', $roles)) 
    {
        remove_menu_page('edit.php?post_type=websites');
    }
  }
  add_action('admin_menu', 'restrict_website_admin_view');

# Custom post type plugin shortcode

You will use the shortcode in your content or page editor.Your shortcode is 

[User_CPT_Form]

and make sure it's included in the content.

