<?php
add_filter( 'get_the_content_limit_allowedtags', 'get_the_content_limit_custom_allowedtags' );
function get_the_content_limit_custom_allowedtags() {
	// Add custom tags to this string
	return '<div>,<span>,<br />'; 
}

add_filter ( 'the_content', 'swniace_replacewith_resources_page');
function swniace_replacewith_resources_page($content){
    require( WPRESOURCESMANAGERPATH . '/includes/swniace-resources-manager-submission-form_data.php' );
    global $post;
	$options= get_option('swniace_resources_manager_settings');
	$SubmissionFormPageID=$options['SubmissionFormPageID'];
    $ResourceRatingsFlag=$options['ResourceRatingsFlag'];
    if($post->post_type == 'niace_resources' && !is_archive()){
        $content = do_shortcode($content);
        $resource_type = get_post_meta($post->ID, "niace_resources_type", true);
        echo "<div class='resource_content'>";
        echo "<h2>$post->post_title</h2>";
        echo "<div>".nl2br($content)."</div>";
        if($ResourceRatingsFlag){
            wp_register_script( 'niace_resource_rating', plugins_url('../js/niace_resource_rating.js', __FILE__), 'jQuery');
            wp_enqueue_script('niace_resource_rating');
            $protocol = isset ( $_SERVER["HTTPS"])? 'https://':'http://';
            $params=array(
                'ajaxurl'=>admin_url( 'admin-ajax.php', $protocol)
            );
            wp_localize_script( 'niace_resource_rating', 'ajaxobject', $params );
            echo niace_resource_ratings($post->ID);
        }
        echo "</div>";
        echo "<div class='resource_data'>";
        echo "<h3>Resource Data</h3>";
        foreach($resource_types as $resource_type_key=>$value){
            if(machinename($value)===$resource_type){
                echo "<div class='resource_types'>Resource Type: $value</div>";
                break;                
            }

        }


        foreach($metafields as $fieldname => $data){

            if(in_array($resource_type_key,$data['usedin'])){
                if($resource_type === 'picture' && machinename($fieldname)==='file'){
                    echo get_resource_picture();
                }
                //echo machinename($fieldname);
                echo get_resource_metadata($fieldname);
            }
        }
        echo "</div>";
        
    }
    elseif($post->post_type == 'niace_resources' && is_archive()){
        $content = preg_replace("/\[caption.*\[\/caption\]/", '',$content);
        if($ResourceRatingsFlag){
            wp_register_script( 'niace_resource_rating', plugins_url('../js/niace_resource_rating.js', __FILE__), 'jQuery');
            wp_enqueue_script('niace_resource_rating');
            $protocol = isset ( $_SERVER["HTTPS"])? 'https://':'http://';
            $params=array(
                'ajaxurl'=>admin_url( 'admin-ajax.php', $protocol)
            );
            wp_localize_script( 'niace_resource_rating', 'ajaxobject', $params );
            
            echo "<div class='niace_resource_rating_container'>". niace_resource_ratings($post->ID)."</div>"
                    . "<div class='niace_resource_archive_container'>".wp_trim_words( $content, 55, null )."</div>";
        }
        else{
            return do_shortcode($content);
        }
        
    }
    else{
        return do_shortcode($content);
    }
}
?>
