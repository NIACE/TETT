<?php

function swniace_resources_manager_init() {
    wp_enqueue_script('jquery');
    $options= get_option('swniace_resources_manager_settings');
    $options['ResourceRatingsFlag'] = "OR";
    update_option('swniace_resources_manager_settings', $options);
}
add_action('init', 'swniace_resources_manager_init');

?>