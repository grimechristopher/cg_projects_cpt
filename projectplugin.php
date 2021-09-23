<?php
   /*
   Plugin Name: Projects Custom Post Type
   Plugin URI: 
   description: This plugin adds a custom projects post type to contain a portfolio of projects
   Version: 1
   Author: Chris Grime
   Author URI: http://chrisgrime.com
   */

// The custom function to register a custom article post type
function custom_post_project() {

    $labels = array(
    'name'               => __( 'Projects' ),
    'singular_name'      => __( 'Project' ),
    'add_new'            => __( 'Add New Project' ),
    'add_new_item'       => __( 'Add New Project' ),
    'edit_item'          => __( 'Edit Project' ),
    'new_item'           => __( 'New Project' ),
    'all_items'          => __( 'All Projects' ),
    'view_item'          => __( 'View Project' ),
    'search_items'       => __( 'Search Project' ),
    'not_found'          => __( 'No Projects found' ),
    'not_found_in_trash' => __( 'No Projects found in the Trash' ), 
    'featured_image'     => 'Featured Image',
    'set_featured_image' => 'Add Featured Image', 
    'menu_name'          => 'Projects'
    );

    $args = array(
    'labels'            => $labels,
    'description'       => 'Holds our project specific data',
    'public'            => true,
    'menu_position'     => 5,
    'supports'          => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
    'has_archive'       => true,
    'show_in_admin_bar' => true,
    'show_in_nav_menus' => true,
    'query_var'         => true,
    );
    // Call the actual WordPress function
    // Parameter 1 is a name for the post type
    // Parameter 2 is the $args array
    register_post_type( 'project', $args);
}
add_action( 'init', 'custom_post_project' );

function add_links_meta_box() {
    add_meta_box("project-links-meta-box", "Project Links", "links_meta_box_markup", "project", "normal", "high", null);
}
add_action("add_meta_boxes", "add_links_meta_box");

function links_meta_box_markup($object) {
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    ?>
        <div>
            <label for="meta-box-link-github">Github Link</label><br>
            <input name="meta-box-link-github" type="url" value="<?php echo get_post_meta( get_the_ID(), "meta-box-link-github", true); ?>">
            
            <br>

            <label for="meta-box-link-github">Live Project Link</label><br>
            <input name="meta-box-link-live-project" type="url" value="<?php echo get_post_meta( get_the_ID(), "meta-box-link-live-project", true); ?>">
        </div>
    <?php  
}

function save_links_meta_box($post_id, $post, $update) {

    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__))) {
        return $post_id;
    }

    if(!current_user_can("edit_post", $post_id)) {
        return $post_id;
    }


    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) {
        return $post_id;
    }

    $slug = "project";
    if($slug != $post->post_type)
        return $post_id;

    $meta_box_github_value = "";
    $meta_box_liveproject_value = "";

    if(isset($_POST["meta-box-link-github"])) {
        $meta_box_github_value = $_POST["meta-box-link-github"];
    }   
    update_post_meta($post_id, "meta-box-link-github", $meta_box_github_value);

    if(isset($_POST["meta-box-link-live-project"])) {
        $meta_box_liveproject_value = $_POST["meta-box-link-live-project"];
    }   
    update_post_meta($post_id, "meta-box-link-live-project", $meta_box_liveproject_value);
}
add_action("save_post", "save_links_meta_box", 10, 3);

?>
