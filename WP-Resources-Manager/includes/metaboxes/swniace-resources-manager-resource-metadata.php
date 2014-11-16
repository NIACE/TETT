<?php
add_action( 'add_meta_boxes', 'swniace_resources_manager_meta_box' );

function swniace_resources_manager_meta_box(){
	add_meta_box('swniace_resources_manager_meta_box','Resource Data','swniace_resources_manager_meta_box_function','niace_resources','normal','high');
}

function swniace_resources_manager_meta_box_function( $post ){
                wp_enqueue_script('jquery-ui-datepicker');
                $ui_themeUrl = plugins_url('../css/smoothness/jquery-ui-custom.min.css', dirname(__FILE__));
                wp_enqueue_style('jquery-ui-theme', $ui_themeUrl);
    $resource_type = get_post_meta($post->ID, "niace_resources_type", true);
    require_once( WPRESOURCESMANAGERPATH . '/includes/swniace-resources-manager-submission-form_data.php' );
    foreach($resource_types as $resource_type_key=>$value){
        if(machinename($value)===$resource_type)
            break;
    }


    foreach($metafields as $fieldname => $data){
        if(in_array($resource_type_key,$data['usedin'])){
            $id = "niace_resources_".machinename($fieldname);
            $the_metadata[$fieldname] = get_post_meta($post->ID, $id, true);
            if($fieldname == "Date"){
                $alternate_date_id = $id.'_alternate_date';
                $the_metadata["alternate_date"] = get_post_meta($post->ID, $alternate_date_id, true);
            }
        }
    }
    
                echo "<label for='resource_type' class='resourcetype' >Resource Type</label>
                <select id='resource_type' name='resource_type' disabled='disabled'>
                <option value=''>Please Select</option>\n";
                foreach($resource_types as $resource_type_id=>$the_resource_type){
                    $str = machinename($the_resource_type);
                    echo "<option value='$str'".selected( $resource_type, $str).">$the_resource_type</option>\n";
                }
                echo "</select>\n";
                
                foreach($metafields as $fieldname => $data){
                    if(isset($the_metadata[$fieldname])){
                        $value = $the_metadata[$fieldname];
                        $class = "formfield";
                        foreach($data['usedin'] as $usedinID){
                            $class .= " ".machinename($resource_types[$usedinID]);
                        }
                        $id = "niace_resources_".machinename($fieldname);
                        echo "<div class='fieldcontainer $class'>\n";
                        switch($data['type']){
                            case "text": 
                                echo "<label for='$id'  class='$class'>".$fieldname.":</label>\n";
                                echo "<input type='text' name='meta[$id]' id='$id' class='$class' value='$value'/><br />\n";
                                break;
                            case "file": 
                                echo "<label for='$id'  class='$class'>".$fieldname.":</label>\n";
                                //echo "<input type='file' name='$id' id='$id' class='$class' /><br />\n";
                                echo "<div class='uploader'>
                                <input type='text' name='meta[$id]' id='$id' value='$value'/>
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
                                echo "<input type='text' name='meta[$id]' id='$id' class='$class' value='$value'/>\n";
                                $alternate_date_id = $id.'_alternate_date';
                                echo "<input type='hidden' name='meta[$alternate_date_id]' id='$alternate_date_id' class='$class' value='".$the_metadata['alternate_date']."' />\n";
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
                        echo "</div>\n";
                    }
                }
                
    //echo "<pre>";
    //print_r($the_metadata);
    //echo "</pre>";
    
}

add_action( 'save_post', 'swniace_resources_manager_meta' );

function swniace_resources_manager_meta( $post_id ){

    if(count($_POST['meta'])){
        foreach($_POST['meta'] as $metadata_key=>$metadata_value){
            update_post_meta($post_id, $metadata_key, $metadata_value);
        }
    }
}
?>