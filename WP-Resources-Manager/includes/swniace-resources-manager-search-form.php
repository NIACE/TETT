<?php

add_filter ( 'the_content', 'swniace_replacewith_search_form');

function swniace_replacewith_search_form($content){
    $options= get_option('swniace_resources_manager_settings');
	$SearchFormPageID=$options['SearchFormPageID'];
    if(is_page($SearchFormPageID)){
        wp_register_script( 'categoriesformmanagement', plugins_url('../js/niace_resource_categories_form_management.js', __FILE__), FALSE );
        wp_enqueue_script( 'categoriesformmanagement' );
            $protocol = isset ( $_SERVER["HTTPS"])? 'https://':'http://';
            $params=array(
                'ajaxurl'=>admin_url( 'admin-ajax.php', $protocol)
            );
            wp_localize_script( 'categoriesformmanagement', 'ajaxobject', $params );
        return get_niace_resource_categories();
    }
    else{
        return $content;
    }
}
?>