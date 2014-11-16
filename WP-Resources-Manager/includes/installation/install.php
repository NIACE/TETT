<?php
function swniace_resources_manager_install() {
    //first check we have no pages called "resources" or "resource_category". These need to be reserved for the custom post slug and the custom taxonomy slyg respectively
    if(get_page_by_path('resources') != NULL||get_page_by_path('resource_category') != NULL){
        die('You cannot use this plugin if you have pages with slugs = "resources" or "resource_category" as these are reserved for use by the plugin');
    }
}
register_activation_hook( WPRESOURCESMANAGERMAINFILEPATH , 'swniace_resources_manager_install' );
?>
