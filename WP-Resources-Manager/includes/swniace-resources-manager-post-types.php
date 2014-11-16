<?php

/* Set up the post types. */
add_action( 'init', 'swniace_resources_manager_register_post_types' );

/* Registers post types. */
function swniace_resources_manager_register_post_types() {

    /* Set up the arguments for the 'music_album' post type. */
    $resources_args = array(
        'public' => true,
        'query_var' => 'niace_resources',
        'rewrite' => array(
            'slug' => 'resources',
            'with_front' => false,
        ),
        'supports' => array(
            'title',
            'editor',
            'thumbnail',
            'post-formats'
        ),
        'labels' => array(
            'name' => 'Resources',
            'singular_name' => 'Resource',
            'add_new' => 'Add New Resource',
            'add_new_item' => 'Add New Resource',
            'edit_item' => 'Edit Resource',
            'new_item' => 'New Resource',
            'view_item' => 'View Resource',
            'search_items' => 'Search Resources',
            'not_found' => 'No Resources Found',
            'not_found_in_trash' => 'No Resources Found In Trash'
        ),
    );

    /* Register the music album post type. */
    register_post_type( 'niace_resources', $resources_args );
    
    add_filter( 'wp_unique_post_slug_is_bad_hierarchical_slug', 'resources_is_bad_hierarchical_slug', 10, 4 );
    function resources_is_bad_hierarchical_slug( $is_bad_hierarchical_slug, $slug, $post_type, $post_parent ) {
       // This post has no parent and is a "base" post
       if ( !$post_parent && $slug == 'resources' )
          return true;
       return $is_bad_hierarchical_slug;
    }

    add_filter( 'wp_unique_post_slug_is_bad_flat_slug', 'resources_is_bad_flat_slug', 10, 3 );
    function resources_is_bad_flat_slug( $is_bad_flat_slug, $slug, $post_type ) {
       if ( $slug == 'resources' )
          return true;
       return $is_bad_flat_slug;
    }
}

?>