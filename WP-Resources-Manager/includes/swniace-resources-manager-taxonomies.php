<?php
class taxonomy_setup {
     function __construct() {
          register_activation_hook( WPRESOURCESMANAGERMAINFILEPATH ,array($this,'activate'));
          add_action( 'init', array( $this, 'create_taxonomies' ) );
     } 
     
     function activate() {
          $this->create_taxonomies();
          $this->populate_taxonomies();
     }
     
     function populate_taxonomies(){
        require_once( WPRESOURCESMANAGERPATH . '/includes/swniace-resources-manager-initialisation_data.php' );
        $taxonomiesPopulated = get_option('swniace_resources_manager_taxonomiesPopulated');
        //$taxonomiesPopulated = false;
        if(!$taxonomiesPopulated){
            delete_option('swniace_resources_manager_target_groupings');
            delete_option('swniace_resources_manager_disability_groupings');
            add_option('swniace_resources_manager_target_groupings', $target_groupings);
            add_option('swniace_resources_manager_disability_groupings', $disability_groupings);

            ksort($curriculum_array);
            foreach($curriculum_array as $curriculum_entry){
                $id = wp_insert_term($curriculum_entry, 'resource_curriculum_category');
            }

            $tag_meta_data = array();
            ksort($targets_array);
            foreach($targets_array as $target_grouping_id => $disability_groupings){
                ksort($disability_groupings);
                foreach($disability_groupings as $disability_grouping_id => $targets){
                    ksort($targets);
                    foreach($targets as $ordinal => $target){
                        $targetid = wp_insert_term($target, 'resource_target_category');
                        if(is_array($targetid)){
                            $tag_meta_data[$targetid['term_id']]['target_name'] = $target;
                            $tag_meta_data[$targetid['term_id']]['target_grouping'] = $target_grouping_id;
                            $tag_meta_data[$targetid['term_id']]['disability_grouping'] = $disability_grouping_id;
                            $tag_meta_data[$targetid['term_id']]['order'] = $ordinal;
                        }
                    }  
                }            
            }
            delete_option('swniace_resources_manager_category_meta');
            add_option('swniace_resources_manager_category_meta', $tag_meta_data);
            update_option('swniace_resources_manager_taxonomiesPopulated', 1);
        }
     }
     
     function create_taxonomies() {
        /* Set up the resource tags taxonomy arguments. */
        $resource_tag_args = array(
            'hierarchical' => true,
            'query_var' => 'niace_resource_curriculum_categories', 
            'show_tagcloud' => true,
            'rewrite' => array(
                'slug' => 'resource_curriculum_category',
                'with_front' => false,
                'hierarchical' => false
            ),

            'labels' => array(
                'name' => 'Resource Curricula',
                'singular_name' => 'Resource Curriculum',
                'edit_item' => 'Edit Resource Curriculum',
                'update_item' => 'Update Resource Curriculum',
                'add_new_item' => 'Add New Resource Curriculum',
                'new_item_name' => 'New Resource Curriculum Name',
                'all_items' => 'All Resource Curricula',
                'search_items' => 'Search Resource Curricula',
                'popular_items' => 'Popular Resource Curricula',
                'separate_items_with_commas' => 'Separate resource Curricula with commas',
                'add_or_remove_items' => 'Add or remove resource Curricula',
                'choose_from_most_used' => 'Choose from the most popular resource Curricula',
            ),
        );

        /* Register the resource tags taxonomy. */
        register_taxonomy( 'resource_curriculum_category', array( 'niace_resources' ), $resource_tag_args );
        
        $resource_tag_args = array(
            'hierarchical' => true,
            'query_var' => 'niace_resource_target_categories', 
            'show_tagcloud' => true,
            'rewrite' => array(
                'slug' => 'resource_target_category',
                'with_front' => false,
                'hierarchical' => false
            ),

            'labels' => array(
                'name' => 'Resource Targets',
                'singular_name' => 'Resource Target',
                'edit_item' => 'Edit Resource Target',
                'update_item' => 'Update Resource Target',
                'add_new_item' => 'Add New Resource Target',
                'new_item_name' => 'New Resource Target Name',
                'all_items' => 'All Resource Targets',
                'search_items' => 'Search Resource Targets',
                'popular_items' => 'Popular Resource Targets',
                'separate_items_with_commas' => 'Separate resource Targets with commas',
                'add_or_remove_items' => 'Add or remove resource Targets',
                'choose_from_most_used' => 'Choose from the most popular resource Targets',
            ),
        );

        /* Register the resource tags taxonomy. */
        register_taxonomy( 'resource_target_category', array( 'niace_resources' ), $resource_tag_args );
 
        
        $resource_tag_args = array(
            'hierarchical' => false,
            'query_var' => 'niace_resource_personalised_categories', 
            'show_tagcloud' => true,
            'rewrite' => array(
                'slug' => 'resource_personalised_category',
                'with_front' => false,
                'hierarchical' => false
            ),
            'labels' => array(
                'name' => 'Resource Tags',
                'singular_name' => 'Resource Tag',
                'edit_item' => 'Edit Resource Tag',
                'update_item' => 'Update Resource Tag',
                'add_new_item' => 'Add New Resource Tag',
                'new_item_name' => 'New Resource Tag Name',
                'all_items' => 'All Resource Tags',
                'search_items' => 'Search Resource Tags',
                'popular_items' => 'Popular Resource Tags',
                'separate_items_with_commas' => 'Separate resource Tags with commas',
                'add_or_remove_items' => 'Add or remove resource Tags',
                'choose_from_most_used' => 'Choose from the most popular resource Tags',
            ),
        );

        /* Register the resource tags taxonomy. */
        register_taxonomy( 'resource_personalised_category', array( 'niace_resources' ), $resource_tag_args );
     }
}

$set_up_taxonomies = new taxonomy_setup();

/* Set up the taxonomies. */
add_action( 'init', 'swniace_resources_manager_protect_taxonomies' );

/* Registers taxonomies. */
function swniace_resources_manager_protect_taxonomies() {
    add_filter( 'wp_unique_post_slug_is_bad_hierarchical_slug', 'resource_category_is_bad_hierarchical_slug', 10, 4 );
    function resource_category_is_bad_hierarchical_slug( $is_bad_hierarchical_slug, $slug, $post_type, $post_parent ) {
       // This post has no parent and is a "base" post
       if ( !$post_parent && $slug == 'resource_category' )
          return true;
       return $is_bad_hierarchical_slug;
    }

    add_filter( 'wp_unique_post_slug_is_bad_flat_slug', 'resource_category_is_bad_flat_slug', 10, 3 );
    function resource_category_is_bad_flat_slug( $is_bad_flat_slug, $slug, $post_type ) {
       if ( $slug == 'resource_category' )
          return true;
       return $is_bad_flat_slug;
    }
}

add_action('admin_footer-post-new.php', 'admin_edit_niace_resource_categories_edit_meta_foot', 11);

/* load scripts in the footer */
function admin_edit_niace_resource_categories_edit_meta_foot() {
    if (isset($_GET['post_type']) && $_GET['post_type'] == 'niace_resources')
    {
        echo '<script type="text/javascript" src="', plugins_url('../js/niace_resource_categories_metabox.js', __FILE__), '"></script>';
    }
}
?>