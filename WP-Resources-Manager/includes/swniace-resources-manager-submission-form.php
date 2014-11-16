<?php

add_filter ( 'the_content', 'swniace_replacewith_submission_form');

function swniace_replacewith_submission_form($content){
	$options= get_option('swniace_resources_manager_settings');
	$SubmissionFormPageID=$options['SubmissionFormPageID'];
    if(is_page($SubmissionFormPageID)){

add_action( 'after_wp_tiny_mce', 'custom_after_wp_tiny_mce' );
function custom_after_wp_tiny_mce() {
    print( '<script type="text/javascript">
        function return_niace_description(){
            var content = tinyMCE.activeEditor.getContent();
            return content;
        }
        </script>');
}

        require( WPRESOURCESMANAGERPATH . '/includes/swniace-resources-manager-submission-form_data.php' );
        $output = "";
        if (isset($_GET['guest']) || is_user_logged_in()){ // kevin@joinitup.co.uk allow non-logged in guests to upload
            if( isset($_POST['action']) ){
                $curriculumTaxonomy = 'resource_curriculum_category'; //Choose the taxonomy
                $targetTaxonomy = 'resource_target_category'; //Choose the taxonomy
                $personalisedTaxonomy = 'resource_personalised_category';
                global $current_user;
                get_currentuserinfo();
       
                switch($_POST['action']){
                    case "submitresource":
                    $resource_type = $_POST['resource_type'];
                    $submission_errors =array();
                    if(!count($_POST['term_id']['curriculum'])){
                        $submission_errors[] = "You must provide a Curriculum tag (first panel)";
                    }
                    if(!count($_POST['term_id']['target'])){
                        $submission_errors[] = "You must provide an Equality and Diversity Specific Tag";
                    }
                    $temp_additional_tags = explode(",", $_POST['additional_tags']);
                    $additional_tags = array();
                    foreach($temp_additional_tags as $tag){
                        if(trim($tag)!==""){
                            $additional_tags[trim($tag)] = trim($tag);
                        }
                    }
                    if(!count($additional_tags)){
                        $submission_errors[] = "You must provide at least one of your own tags.";
                    }
                    if($_POST['resource_title'] == ""){
                        $submission_errors[] = "Title is a required field";
                    }
                    if($_POST['resource_description'] == ""){
                        $submission_errors[] = "Description is a required field";
                    }
                    if($resource_type == "onlineresource"){
                        if($_POST['meta']['niace_resources_link'] == ""){
                            $submission_errors[] = "Resource Link is a required field";
                        }
                    }
                    elseif($_POST['meta']['niace_resources_file'] == ""){
                        $submission_errors[] = "Resource File is a required field";
                    }
                    if($resource_type != "" && !count($submission_errors)){
                        unset($post_data['ID']);
                        $post_data['post_status'] = is_user_logged_in() ? 'publish' : 'pending';  // kevin@joinitup.co.uk - mark guest uploads as 'pending'
                        $post_data['post_title'] = $_POST['resource_title'];
                        $post_data['post_content'] = $_POST['resource_description'];
                        $post_data['post_type'] = 'niace_resources';
                        $post_data['post_author'] = $current_user->ID;
                        $post_data['comment_status'] = 'closed';
                        $post_data['ping_status'] = 'closed';
                        //$post_data['post_category'] = array(1); // the default 'Uncatrgorised'
                        // Insert the post into the database
                        $the_post_id = wp_insert_post( $post_data );
                        add_post_meta($the_post_id, 'niace_resources_type', $resource_type, true);
                        foreach($_POST['meta'] as $metadata_key=>$metadata_value){
                            add_post_meta($the_post_id, $metadata_key, $metadata_value, true);
                        }
                        add_post_meta($the_post_id, 'niace_resource_rating_average', 0, false);
                        $tagwith=array();

                        

                        foreach($_POST['term_id'] as $type =>$terms){
                            foreach($terms as $term_id){
                                $tagwith[$type][]=$term_id;
                            }
                        }
                        $tagwith['curriculum'] = array_map( 'intval', $tagwith['curriculum'] );
                        $tagwith['curriculum'] = array_unique( $tagwith['curriculum'] );
                        wp_set_object_terms( $the_post_id, $tagwith['curriculum'], $curriculumTaxonomy);
                        $tagwith['target'] = array_map( 'intval', $tagwith['target'] );
                        $tagwith['target'] = array_unique( $tagwith['target'] );
                        wp_set_object_terms( $the_post_id, $tagwith['target'], $targetTaxonomy);
                        
                        
                        foreach($additional_tags as $additional_tag){
                            //first see if it exists, in which case link it
                            $tidyterm = ucwords(strtolower(trim($additional_tag)));
                            $test_exists = term_exists($tidyterm, $personalisedTaxonomy);
                            if(!is_array($test_exists)){
                                if($tidyterm>''){
                                    $result = wp_insert_term($tidyterm, $personalisedTaxonomy);
                                    $new_term_id = $result['term_id'];
                                }
                            }
                            else{
                                $new_term_id = $test_exists['term_id'];
                            }
                            $tagwith['personalised'][]=intval($new_term_id);
                        }
                        wp_set_object_terms( $the_post_id, $tagwith['personalised'], $personalisedTaxonomy);

                        echo "<h2>Thank you, the resource <a href='".get_permalink($the_post_id)."'>".$_POST['resource_title']."</a> has been added to the database</h2>";
                        break;
                    }
                    else{
                        if(count($submission_errors) == 1){
                            echo "There was a problem with the submission:<br />";
                            echo "* ".$submission_errors[0]."<br />";
                        }
                        else{
                            echo "There were problems with the submission:<br />";
                            foreach($submission_errors as $submit_error){
                                echo "* ".$submit_error."<br />";
                            }
                        }
                        
                    }
                }
            }
            else{
                wp_enqueue_media();
                wp_enqueue_script('jquery-ui-datepicker');
                $submissionformmanagementScriptUrl = plugins_url('js/submissionformmanagement.js', dirname(__FILE__));
                wp_register_script("submissionformmanagement",$submissionformmanagementScriptUrl, array('jquery'), "", TRUE );
                foreach($resource_types as $resource_type_id=>$resource_type){
                    $fields_array[machinename($resource_type)]=machinename($resource_type);
                }    
                $ui_themeUrl = plugins_url('/css/smoothness/jquery-ui-custom.min.css', dirname(__FILE__));
                wp_enqueue_style('jquery-ui-theme', $ui_themeUrl);
                wp_localize_script( 'submissionformmanagement', 'fieldsoptions', $fields_array );
                wp_enqueue_script( "submissionformmanagement");
                wp_register_script( 'categoriesformdisplay', plugins_url('../js/niace_resource_categories_form_display.js', __FILE__), FALSE );
                
                $protocol = isset ( $_SERVER["HTTPS"])? 'https://':'http://';
                $params=array(
                    'ajaxurl'=>admin_url( 'admin-ajax.php', $protocol)
                );
                wp_localize_script( 'categoriesformdisplay', 'ajaxobject', $params );
        
                wp_enqueue_script( 'categoriesformdisplay' );
                echo "<form action='".$_SERVER['REQUEST_URI']."' id='niace_resources_form' method='post'>";
                echo "<div class='mainfields'>\n";
                
                echo "<label for='resource_type' class='resourcetype' >Resource Type</label>
                <select id='resource_type' name='resource_type'>
                <option value=''>Please Select</option>\n";
                foreach($resource_types as $resource_type_id=>$resource_type){
                    $str = machinename($resource_type);
                    echo "<option value='$str'>$resource_type</option>\n";
                }
                echo "</select>\n";
                echo "<div class='fieldcontainer all'>\n";
                echo "<label for='title' class='formfield'y>Title *</label>\n";
                echo "<input type='text' name='resource_title' id='title' value=''/><br />\n";
                echo "</div>\n";  // fieldcontainer
                foreach($metafields as $fieldname => $data){

                    $class = "formfield";
                    foreach($data['usedin'] as $usedinID){
                        $class .= " ".machinename($resource_types[$usedinID]);
                    }
                    $id = "niace_resources_".machinename($fieldname);
                    echo "<div class='fieldcontainer $class'>\n";
                    switch($data['type']){
                        case "text": 
                            echo "<label for='$id'  class='$class'>".$fieldname.":</label>\n";
                            echo "<input type='text' name='meta[$id]' id='$id' class='$class' value=''/><br />\n";
                            break;
                        case "file": 
                            echo "<label for='$id'  class='$class'>".$fieldname.":</label>\n";
                            //echo "<input type='file' name='$id' id='$id' class='$class' /><br />\n";
                            echo "<div class='uploader'>
                            <input type='text' name='meta[$id]' id='$id' />
                            <input type = 'button' class='button' name='".$id."_button' id='".$id."_button' value='Upload' />
                            </div>
                            <script>
                            jQuery(document).ready(function($){
                              var _custom_media = true,
                                  _orig_send_attachment = wp.media.editor.send.attachment;

                              $('#".$id."_button').click(function(e) {
                                var send_attachment_bkp = wp.media.editor.send.attachment;
                                var button = $(this);
                                var id = button.attr('id').replace('_button', '');
                                _custom_media = true;
                                wp.media.editor.send.attachment = function(props, attachment){
                                  if ( _custom_media ) {
                                    $('#'+id).val(attachment.url);
                                  } else {
                                    return _orig_send_attachment.apply( this, [props, attachment] );
                                  };
                                }

                                wp.media.editor.open(button);
                                return false;
                              });

                              $('.add_media').on('click', function(){
                                _custom_media = false;
                              });
                            });
                            </script>";
                            break;
                        case "date": 
                            echo "<label for='$id' class='$class'>".$fieldname."</label>\n";
                            echo "<input type='text' name='meta[$id]' id='$id' class='$class' value=''/>\n";
                            $alternate_date_id = $id.'_alternate_date';
                            echo "<input type='hidden' name='meta[$alternate_date_id]' id='$alternate_date_id' class='$class' value='' />\n";
                            echo "<script>
                            jQuery(function() {
                                jQuery( '#$id' ).datepicker({
                                          showOn: 'focus',
                                          dateFormat: 'dd/mm/yy',
                                          altField: '#$alternate_date_id',
                                          altFormat: '@'
                                      });
                            });
                            </script>\n";
                            break;
                    }
                    echo "</div>\n"; // fieldcontainer
                }


                echo "<div class='fieldcontainer all'>\n";
                echo "    <label for='description'>Description *</label><br />\n";
                wp_editor('',"resource_description", array('textarea_rows'=>12, 'editor_class'=>'resource_description_class'));

                echo "    <input type=\"button\" id=\"fields-completed\" value=\"Next\">";
                echo "</div>\n";  // fieldcontainer
                echo "</div>\n";  // mainfields


                echo "<div id=\"tag-detail\" class='tagfields fieldcontainer all'>\n";
                echo "    <span id=\"tag-instructions\"><p><em>Now tag your resource - add key words to help someone searching for specific resources.</em></p></span>";
                echo get_niace_resource_categories('',false);
                echo "</div>\n";

                echo "<span id=\"user-tags\">\n";
                echo "    <p>To finish the tagging process and to enable others to find resources easily provide additional keywords that would support a search in the <strong>Your Tags and those of other users</strong> box. Use a comma to separate the tags.</p>";
                echo "    <label for='title' class='formfield'y>Your Tags and those of other users</label>\n";
                echo "    <input type='text' name='additional_tags' id='additionaltags' value=''/><br />\n";
                echo "    <p>&nbsp;</p>";
                echo "    <input type='submit' name='submit' value='Submit' />";
                echo "    <input type='hidden' name='action' value='submitresource' />\n";
                echo "</span>\n";

                echo "</form>";

            }
        }
        else{
            echo "<p>You must be logged in to create an entry</p>";
        }

        //echo  $output;
    }
    else{
        return $content;
    }
}

?>