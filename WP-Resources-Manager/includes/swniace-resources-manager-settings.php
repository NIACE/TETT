<?php
add_action( 'admin_menu', 'swniace_resources_manager_add_settings_menu' );

function swniace_resources_manager_add_settings_menu() {
	$parent_slug='edit.php?post_type=niace_resources';
	$page_title='Options';
	$menu_title='Options';
	$capability='manage_options';
	$menu_slug='_resources_manager_options';
	$function='swniace_resources_manager_settings_page';
	add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
}


add_action('admin_init', 'swniace_resources_manager_admin_settings_init');

function swniace_resources_manager_admin_settings_init() {
	$option_group='swniace_resources_manager_settings';
	$option_name='swniace_resources_manager_settings';
	$sanitize_callback='swniace_resources_manager_validate_settings';
	register_setting( $option_group, $option_name,$sanitize_callback);

  
	$id='swniace_resources_manager_SubmissionFormPage';
	$title='Submission';
	$callback='swniace_resources_manager_SubmissionFormPage_text';
	$page='swniace_resources_manager_settings_page';
	add_settings_section( $id, $title, $callback, $page );
	
	$id='swniace_resources_manager_SubmissionForm';
	$title='Resources Submission Form';
	$callback='swniace_resources_manager_SubmissionFormPage_form';
	$page='swniace_resources_manager_settings_page';
	$section='swniace_resources_manager_SubmissionFormPage';
	$args='';
    
	add_settings_field( $id, $title, $callback, $page, $section, $args );	  
	$id='swniace_resources_manager_SearchFormPage';
	$title='Search';
	$callback='swniace_resources_manager_SearchFormPage_text';
	$page='swniace_resources_manager_settings_page';
	add_settings_section( $id, $title, $callback, $page );
	
	$id='swniace_resources_manager_SearchForm';
	$title='Resources Search Form';
	$callback='swniace_resources_manager_SearchFormPage_form';
	$page='swniace_resources_manager_settings_page';
	$section='swniace_resources_manager_SearchFormPage';
	$args='';
	add_settings_field( $id, $title, $callback, $page, $section, $args );	
    
 
	$id='swniace_resources_manager_Resource_Ratings';
	$title='Resource Ratings';
	$callback='swniace_resources_manager_Resource_Ratings_text';
	$page='swniace_resources_manager_settings_page';
	add_settings_section( $id, $title, $callback, $page );
	
	$id='swniace_resources_manager_Resource';
	$title='Resource Ratings';
	$callback='swniace_resources_manager_Resource_Ratings_form';
	$page='swniace_resources_manager_settings_page';
	$section='swniace_resources_manager_Resource_Ratings';
	$args='';
	add_settings_field( $id, $title, $callback, $page, $section, $args );	
    
	$id='swniace_resources_manager_Search_Operator';
	$title='Search Operator';
	$callback='swniace_resources_manager_Search_Operator_text';
	$page='swniace_resources_manager_settings_page';
	add_settings_section( $id, $title, $callback, $page );
	
	$id='swniace_resources_manager_Search';
	$title='Search Operator';
	$callback='swniace_resources_manager_Search_Operator_form';
	$page='swniace_resources_manager_settings_page';
	$section='swniace_resources_manager_Search_Operator';
	$args='';
	add_settings_field( $id, $title, $callback, $page, $section, $args );	


}

function swniace_resources_manager_validate_settings($input){
	$valid=array();
	$valid['SubmissionFormPageID']=$input['SubmissionFormPageID'];
    $valid['SearchFormPageID']=$input['SearchFormPageID'];
    $valid['ResourceRatingsFlag']=0;
    switch($input['defaultSearchOperator']){
        case "AND":$valid['defaultSearchOperator'] = "AND";
            break;
        default:$valid['defaultSearchOperator'] = "OR";
    }
    
    if(isset($input['ResourceRatingsFlag'])&&$input['ResourceRatingsFlag']==1){
        $valid['ResourceRatingsFlag']=1;
    }
	return $valid;
}

function swniace_resources_manager_Resource_Ratings_text() {
	echo '<p>Display ratings block on resource page</p>';
}

function swniace_resources_manager_Resource_Ratings_form() {
	$options= get_option('swniace_resources_manager_settings');
	$ResourceRatingsFlag=$options['ResourceRatingsFlag'];
    echo "<input type='checkbox' name='swniace_resources_manager_settings[ResourceRatingsFlag]' value=1 ".checked( $ResourceRatingsFlag, 1, false)." />";
}

function swniace_resources_manager_SubmissionFormPage_text() {
	echo "<p>Select page to display submission form</p>";
}

function swniace_resources_manager_SubmissionFormPage_form() {
	$options= get_option('swniace_resources_manager_settings');
	$SubmissionFormPageID=$options['SubmissionFormPageID'];
echo "<select name='swniace_resources_manager_settings[SubmissionFormPageID]'> 
        <option value='0'>". esc_attr( __( 'Select page' ) ). "</option>";
        $pages = get_pages(); 
            foreach ( $pages as $page ) {
                $option = '<option value="' . $page->ID  . '"'.selected( $page->ID, $SubmissionFormPageID, false).'>';
                $option .= $page->post_title;
                $option .= '</option>';
            echo $option;
        }
    echo "</select>";

}

function swniace_resources_manager_SearchFormPage_text() {
	echo '<p>Select page to display search form</p>';
}

function swniace_resources_manager_SearchFormPage_form() {
	$options= get_option('swniace_resources_manager_settings');
	$SearchFormPageID=$options['SearchFormPageID'];
echo "<select name='swniace_resources_manager_settings[SearchFormPageID]'> 
        <option value='0'>". esc_attr( __( 'Select page' ) ). "</option>";
        $pages = get_pages(); 
            foreach ( $pages as $page ) {
                $option = '<option value="' . $page->ID  . '"'.selected( $page->ID, $SearchFormPageID, false).'>';
                $option .= $page->post_title;
                $option .= '</option>';
            echo $option;
        }
    echo "</select>";

}


function swniace_resources_manager_Search_Operator_text() {
	echo '<p>Define the default boolean Search Operator (AND/OR) to be used in searching for items across Curriculum tags and Target (Type/Level/Protected groups/Accessibility) tags</p>';
    echo '<p>AND - will return a list of Personalised tags used on items that have all the selected Curriculum tags and Target tags.</p>';
    echo '<p>OR - will return a list of Personalised tags used on items that have all the selected Curriculum tags OR Target tags.</p>';
    echo '<p>AND will return a more restricted result set than OR.</p>';
}

function swniace_resources_manager_Search_Operator_form() {
	$options= get_option('swniace_resources_manager_settings');
	$defaultSearchOperator = $options['defaultSearchOperator'];
echo "<select name='swniace_resources_manager_settings[defaultSearchOperator]'>"
        . "<option value='AND'".selected( 'AND', $defaultSearchOperator, false).">AND</option>"
        . "<option value='OR'".selected( 'OR', $defaultSearchOperator, false).">OR</option>"
        . "</select>";

}

/**/
function swniace_resources_manager_settings_page() {
ob_start();
?>
<div class="wrap">
    <form method="post" action="options.php">
    <?php
        settings_fields( 'swniace_resources_manager_settings' );
        do_settings_sections( 'swniace_resources_manager_settings_page' );
        submit_button();
    ?>
    </form>
</div>
<?php
echo ob_get_clean();
}
?>
