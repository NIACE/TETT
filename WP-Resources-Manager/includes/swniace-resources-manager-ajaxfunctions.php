<?php
add_action( 'wp_ajax_swniace_resources_manager_ajax_savegroupings', 'swniace_resources_manager_ajax_savegroupings_callback' );
function swniace_resources_manager_ajax_savegroupings_callback() {
	global $wpdb; // this is how you get access to the database
    $TaxonomyGroupNames=get_option('swniace_resources_manager_TaxonomyGroupNames');
    foreach($_POST['changed'] as $changeddata){
        $id = $changeddata[4];
        if($id=='' && $changeddata[3]>''){
            $TaxonomyGroupNames[] = $changeddata[3];
            echo $changeddata[3]." added";
        }
        elseif($TaxonomyGroupNames[$id] == $changeddata[2] && $changeddata[3]>''){
            $TaxonomyGroupNames[$id] = $changeddata[3];
            echo $changeddata[2]." amended to ".$changeddata[3];
        }
        elseif($changeddata[2] !='' && $changeddata[3] ==''){
            unset($TaxonomyGroupNames[$id]);
            echo $changeddata[2]." deleted";
        }
    }
    unset($TaxonomyGroupNames['']);
	update_option('swniace_resources_manager_TaxonomyGroupNames', $TaxonomyGroupNames);
	die(); // this is required to return a proper result
}

add_action( 'wp_ajax_swniace_resources_manager_ajax_loadgroupings', 'swniace_resources_manager_ajax_loadgroupings_callback' );
function swniace_resources_manager_ajax_loadgroupings_callback() {
	$TaxonomyGroupNames=get_option('swniace_resources_manager_TaxonomyGroupNames');
    if(count($TaxonomyGroupNames)==0){
        $TaxonomyGroupNames[0] = "None";
    }
    $data = array();
    foreach ($TaxonomyGroupNames as $groupNameId=>$groupName){
        //$data.="{id:'$id',groupname:'$group'},\n";
        $data[]=array('id'=>$groupNameId,'groupname'=>$groupName);
    }
    echo json_encode($data);
	die(); // this is required to return a proper result
}

add_action( 'wp_ajax_show_hide_tagcloud_elements', 'show_hide_tagcloud_elements_callback' );
add_action( 'wp_ajax_nopriv_show_hide_tagcloud_elements', 'show_hide_tagcloud_elements_callback' );
function show_hide_tagcloud_elements_callback(){
	$options= get_option('swniace_resources_manager_settings');
	$defaultSearchOperator = $options['defaultSearchOperator'];
    $SearchOperator = "OR";
    if($defaultSearchOperator && $defaultSearchOperator == "AND"){
        $SearchOperator = "AND";
    }
    $terms = $_POST['terms'];

    //$output = "<pre>" . print_r($terms, true) . "</pre>";
    //echo($output);
    //die();

    $postlist = get_posts(array(
        'post_type' => 'niace_resources',
        'tax_query' => array(
            'relation' => $SearchOperator,
            array(
            'taxonomy' => 'resource_curriculum_category',
            'field' => 'term_id',
            'terms' => $terms['curriculum']),
            array(
            'taxonomy' => 'resource_target_category',
            'field' => 'term_id',
            'terms' => $terms['target'])
        ))
    );

    // now get each resource_personalised_category for each post
    $posts = array();
    foreach ( $postlist as $post ) {
       $posts[] += $post->ID;
    }

    if(count($posts)){
        $args = array('fields' => 'ids');
        $resource_personalised_category_ids = wp_get_object_terms( $posts, 'resource_personalised_category', $args );

        $args = array(
            'smallest'                  => 12, 
            'largest'                   => 22,
            'unit'                      => 'pt', 
            'number'                    => 45,  
            'format'                    => 'flat',
            'separator'                 => "\n",
            'orderby'                   => 'name', 
            'order'                     => 'ASC',
            'exclude'                   => null, 
            'include'                   => $resource_personalised_category_ids, 
            'topic_count_text_callback' => default_topic_count_text,
            'link'                      => 'view', 
            'taxonomy'                  => 'resource_personalised_category', 
            'echo'                      => true,
            'child_of'                   => null
        );
        wp_tag_cloud( $args );
    }
    else{
        if(count($terms)){
            echo "No results returned for current search";
        }
    }
	die(); // this is required to return a proper result    
}

add_action( 'wp_ajax_resource_rating_submit', 'resource_rating_submit_callback' );
add_action( 'wp_ajax_nopriv_resource_rating_submit', 'resource_rating_submit_callback' );
function resource_rating_submit_callback(){
            $postID = $_POST['postID'];
            $rating = $_POST['rating'];
            add_post_meta($postID, 'niace_resource_rating', $rating, false);
            $current_ratings = get_post_meta($postID, 'niace_resource_rating', false);
            $average = 0;
            if(count($current_ratings)){
                $average = array_sum($current_ratings)/count($current_ratings);
            }
            update_post_meta($postID, 'niace_resource_rating_average', $average);
            echo "Thank you for rating this resources";
            die();
}

?>