<?php
//add_filter ( 'the_content', 'swniace_replacewith_resources_archive');
/*
function swniace_replacewith_resources_archive($content){
    require( WPRESOURCESMANAGERPATH . '/includes/swniace-resources-manager-submission-form_data.php' );
    global $post;
	$options= get_option('swniace_resources_manager_settings');
	$SubmissionFormPageID=$options['SubmissionFormPageID'];
    if($post->post_type == 'niace_resources' && is_archive()){
        return wp_trim_words( $content, 55, null );
    }
    else{
        return $content;
    }
}*/

add_filter ( 'loop_start', 'swniace_insert_sortform');

function swniace_insert_sortform(){
    global $post;
	$options= get_option('swniace_resources_manager_settings');
    $ResourceRatingsFlag=$options['ResourceRatingsFlag'];
    if($post->post_type == 'niace_resources' && is_archive()){
        $sort = 0;
        if(isset($_POST['sort'])){
            $sort = $_POST['sort'];
        }
        echo "<div class='niace_resources_sortby_form'>\n";
        echo "<form method='post'>\n";
        echo "<select name='sort'>\n";
        echo "<option value='0'".selected(0,$sort,false).">Select to sort by...</option>\n";
        echo "<option value='1'".selected(1,$sort,false).">Title A-Z</option>\n";
        echo "<option value='2'".selected(2,$sort,false).">Title Z-A</option>\n";
        if($ResourceRatingsFlag){     
            echo "<option value='3'".selected(3,$sort,false).">Rating High-Low</option>\n";
            echo "<option value='4'".selected(4,$sort,false).">Rating Low-High</option>\n";  
        }  
        echo "</select>\n";
        echo "<input type='submit' name='sortby' value='Sort' />\n";
        echo "</form>\n";
        echo "</div>\n";
    }
}

add_action( 'pre_get_posts', 'my_change_sort_order'); 
    function my_change_sort_order($query){
        if(is_archive() && (get_query_var('niace_resource_curriculum_categories')!='' || get_query_var('niace_resource_target_categories')!='' || get_query_var('niace_resource_personalised_categories')!='' )):
         //If you wanted it for the archive of a custom post type use: is_post_type_archive( $post_type )
            if(isset($_POST['sort'])){
                switch($_POST['sort']){
                    case 1:
                        //Set the order ASC or DESC
                        $query->set( 'order', 'ASC' );
                        //Set the orderby
                        $query->set( 'orderby', 'title' );
                        break;
                    case 2:
                        //Set the order ASC or DESC
                        $query->set( 'order', 'DESC' );
                        //Set the orderby
                        $query->set( 'orderby', 'title' );
                        break;
                    case 3:
                        //Set the order ASC or DESC
                        $query->set( 'order', 'DESC' );
                        //Set the orderby
                        $query->set( 'orderby', 'meta_value_num' );
                        //Set the metaquery
                        $query->set( 'meta_key', 'niace_resource_rating_average' );
                        break;
                    case 4:
                        //Set the order ASC or DESC
                        $query->set( 'order', 'ASC' );
                        //Set the orderby
                        $query->set( 'orderby', 'meta_value_num' );
                        //Set the metaquery
                        $query->set( 'meta_key', 'niace_resource_rating_average' );
                        break;
                }
            }

        endif;    
    };
?>
