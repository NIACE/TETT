<?php
//http://wordpress.stackexchange.com/questions/28467/custom-columns-on-edit-tags-php-main-page

add_filter( "manage_edit-niace_resource_categories_columns", "column_header_function" );  
function column_header_function($columns)  
{  
    //add the column
    $columns['taxonomygrouping'] = 'Grouping';  
    return $columns;  
} 

add_action( "manage_niace_resource_categories_custom_column",  "populate_rows_function", 10, 3  ); 
function populate_rows_function($empty = '', $column_name, $term_id)  
{  
    //populate the column
	$term_meta = get_option( "niace_resource_tag_{$term_id}" );

    $tag_meta= get_option('swniace_resources_manager_category_meta');
    $term_meta = $tag_meta[$term_id];
    switch($column_name){
        case "taxonomygrouping":
            $TaxonomyGroupNames=get_option('swniace_resources_manager_TaxonomyGroupNames');
            if(isLevel(3,$term_id)){
                echo $TaxonomyGroupNames[$term_meta['taxonomygrouping']];
            }
            else{
                echo "--";
            }
            break;
    }
}

add_action('niace_resource_categories_edit_form', 'render_taxonomygrouping_select_field_for_edit');
function render_taxonomygrouping_select_field_for_edit(){
    $term_id = $_GET['tag_ID'];
    if(isLevel(3,$term_id)){
    $tag_meta= get_option('swniace_resources_manager_category_meta');
    $term_meta = $tag_meta[$term_id];
        $TaxonomyGroupNames=get_option('swniace_resources_manager_TaxonomyGroupNames');
        echo "<table class='form-table'>\n";
        echo "<tr class='form-field'>\n";
        echo "<th scope='row' valign='top'><label for='taxonomygrouping'>Level 3 Group</label></th>";
        echo "<td>\n";
        echo "<select name='term_meta[taxonomygrouping]' id='select_taxonomygrouping'>\n";
        foreach($TaxonomyGroupNames as $groupNameId=>$groupName){
            echo "<option value='$groupNameId'".selected( $term_meta['taxonomygrouping'], $groupNameId).">$groupName</option>\n";
        }
        echo "</select>\n";
        echo "<p class='description'>The Level 3 Grouping only effects level 3 tags, grouping them in front end display</p>\n";
        echo "</td>\n";
        echo "</tr>\n";
        echo "</table>\n";
    }
}

add_action('niace_resource_categories_add_form_fields', 'render_taxonomygrouping_select_field_for_add');
function render_taxonomygrouping_select_field_for_add(){
    $TaxonomyGroupNames=get_option('swniace_resources_manager_TaxonomyGroupNames');
    echo "<div class='form-field'>\n";
    echo "<label for='taxonomygrouping'>Level 3 Group</label>";
    echo "<select name='term_meta[taxonomygrouping]' id='select_taxonomygrouping'>\n";
    foreach($TaxonomyGroupNames as $groupNameId=>$groupName){
        echo "<option value='$groupNameId'>$groupName</option>\n";
    }
    echo "</select>\n";
    echo "<p class='description'>The Level 3 Grouping only effects level 3 tags, grouping them in front end display</p>\n";
    echo "</div>\n";
}

add_action('quick_edit_custom_box', 'render_taxonomygrouping_select_field_for_quickedit', 10, 2 );
function render_taxonomygrouping_select_field_for_quickedit($column_name, $post_type){  
    //add the grouping function to the quick edit area
    $term_id = $tag->term_id; // Get the ID of the term you're editing
    //print_r($tag);
    $tag_meta= get_option('swniace_resources_manager_category_meta');
    $term_meta = $tag_meta[$term_id];
    switch($column_name){
        case "taxonomygrouping":
            $TaxonomyGroupNames=get_option('swniace_resources_manager_TaxonomyGroupNames');
            echo "<fieldset class='inline-edit-col-right inline-edit-book'>\n";
            echo "<div class='inline-edit-col column-$column_name'>\n";
            echo "<label class='inline-edit-group'>\n";
            echo "<span class='title'>Level 3 Grouping</span>";
            echo "<select name='term_meta[taxonomygrouping]' id='select_taxonomygrouping'>\n";
            foreach ($TaxonomyGroupNames as $groupNameId=>$groupName){
                echo "<option value='$groupNameId'>$groupName</option>\n";
            }
            echo "</select>\n";
            echo " </label>\n";
            echo "</div>\n";
            echo "</fieldset>\n";
            break;
    }

}


add_action( 'edited_niace_resource_categories', 'save_niace_resource_categories', 10, 2 );
add_action( 'added_niace_resource_categories', 'save_niace_resource_categories', 10, 2 );
// A callback function to save our extra taxonomy field(s)
function save_niace_resource_categories( $term_id ) {
    $tags_meta= get_option('swniace_resources_manager_category_meta');
    $term_meta = $tags_meta[$term_id];
    $cat_keys = array_keys( $_POST['term_meta'] );
	if ( !isset( $_POST['term_meta']['taxonomygrouping'] ) ){
		$term_meta['taxonomygrouping']=0;
	}
    foreach ( $cat_keys as $key ){
        if ( isset( $_POST['term_meta'][$key] ) ){
            $term_meta[$key] = $_POST['term_meta'][$key];
        }
    }
    $tags_meta[$term_id] = $term_meta;
    update_option('swniace_resources_manager_category_meta', $tags_meta);
}

add_action('admin_footer-edit-tags.php', 'admin_edit_niace_resource_categories_foot', 11);

/* load scripts in the footer */
function admin_edit_niace_resource_categories_foot() {
    if (   (isset($_GET['taxonomy']) && $_GET['taxonomy'] == 'niace_resource_categories')
        && (isset($_GET['post_type']) && $_GET['post_type'] == 'niace_resources')
        && !isset($_GET['tag_ID']))
    {
        echo '<script type="text/javascript" src="', plugins_url('../js/niace_resource_categories_inline_edit.js', __FILE__), '"></script>';
    }
}

function isLevel($level, $term_id){
    if(count(get_ancestors( $term_id,'niace_resource_categories' )) == $level-1)
        return true;
    else
        return false;
}

?>