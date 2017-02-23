<?php

function theme_enqueue_styles() {
    wp_enqueue_style( 'avada-parent-stylesheet', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
}
add_action( 'after_setup_theme', 'avada_lang_setup' );

//---------------------------------------------------//
//---- REGISTER CUSTOM POST TYPES ------------------//
//--------------------------------------------------//

function developer_custom_post_type() {
  $labels = [
    'name'               => _x( 'Developers', 'post type general name' ),
    'singular_name'      => _x( 'Developer', 'post type singular name' ),
    'add_new'            => _x( 'Add New Developer', '' ),
    'add_new_item'       => __( 'Add New Developer' ),
    'edit_item'          => __( 'Edit Developer' ),
    'new_item'           => __( 'New Developer' ),
    'all_items'          => __( 'All Developers' ),
    'view_item'          => __( 'View Developer' ),
    'search_items'       => __( 'Search Developers' ),
    'not_found'          => __( 'No developers found' ),
    'not_found_in_trash' => __( 'No developers found in the Trash' ), 
    'parent_item_colon'  => '',
    'menu_name'          => 'Developers',
 ];
  $args = [
    'labels'        => $labels,
    'description'   => 'Holds our Developer information',
    'public'        => true,
    'menu_position' => 5,
    'supports'      => [ 'title', 'editor', 'thumbnail', 'order' ],
    'has_archive'   => true,
    'rewrite' => [ 'slug' => 'developer' ]
  ];
  register_post_type( 'developer', $args ); 
  flush_rewrite_rules();
}
add_action( 'init', 'developer_custom_post_type' );

?>
