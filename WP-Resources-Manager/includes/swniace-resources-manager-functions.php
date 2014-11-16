<?php

function machinename($string){
    return strtolower(preg_replace("/[^A-Za-z0-9]/","",$string));
}

function get_resource_metadata($fieldname){
    require( WPRESOURCESMANAGERPATH . '/includes/swniace-resources-manager-submission-form_data.php' );
    global $post;
    if(isset($metafields[$fieldname]) && is_object($post) && $post->post_type == 'niace_resources'){
        $id = "niace_resources_".machinename($fieldname);
        $value = get_post_meta($post->ID, $id, true);
        if($value){
            switch($fieldname){
                case 'File *':
                    $return = "<div><a href='$value' target='_blank' title='Download Resource $post->post_title'>Download Resource $post->post_title</a></div>";
                    break;
                 case 'Link *':
                    $return = "<div><a href='$value' target='_blank' title='Web Link to $post->post_title' >Web Link to $post->post_title</a></div>";
                    break;
                 case 'Author':
                    $return = "<div>Author: $value</div>";
                    break;
                 case 'Publisher':
                    $return = "<div>Publisher: $value</div>";
                    break;
                 case 'Place':
                    $return = "<div>Location: $value</div>";
                    break;
                 case 'Date':
                    $return = "<div>Date: $value</div>";
                    break;
                 case 'City':
                    $return = "<div>City: $value</div>";
                    break;
                 case 'Region':
                    $return = "<div>Region: $value</div>";
                    break;
                 case 'Interviewer':
                    $return = "<div>Interviewer: $value</div>";
                    break;
                 case 'Interviewee':
                    $return = "<div>Interviewee: $value</div>";
                    break;
                 case 'Institution':
                    $return = "<div>Institution: $value</div>";
                    break;
                 case 'Country':
                    $return = "<div>Country: $value</div>";
                    break;
                 case 'Writer':
                    $return = "<div>Writer: $value</div>";
                    break;
                 case 'Theatre':
                    $return = "<div>Theatre: $value</div>";
                    break;
            }
            return $return;
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }
    
    function niace_star_ratings($post_id){
        
    }
}

function get_resource_picture(){
    require( WPRESOURCESMANAGERPATH . '/includes/swniace-resources-manager-submission-form_data.php' );
    global $post;
    if(is_object($post) && $post->post_type == 'niace_resources'){
        $id = "niace_resources_file";
        $value = get_post_meta($post->ID, $id, true);
        if($value){
            $return = "<div><img  class='niace_resource_image' src='$value' /></div>";
            return $return;
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }
    
}
?>
