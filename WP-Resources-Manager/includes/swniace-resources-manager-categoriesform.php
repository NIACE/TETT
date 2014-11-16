<?php

// filterTagName - remove any non-alpha-numeric characters and convert to lower case
function filterTagName($name) {
    return( preg_replace("/[^a-z0-9]/", "", strtolower($name)) );
}

// matchTaxonomyTerm - match the name of a taxonomy term in the database with a string
function matchTaxonomyTerm( $taxonomyTerms, $matchMe) {
    foreach($taxonomyTerms as $objectTerm) {
        if(filterTagName($objectTerm->name) == filterTagName($matchMe)) {
            return( $objectTerm->term_id ); // id will be one or higher
        }
    }
    return( 0 );
}


// outputCurriculumTerm - convert the curriculum array item into an input checkbox
function outputCurriculumTerm( $curriculum_name, $curriculum_id) {
    $labelclass = intval($curriculum_id) < 1 ? ' tagnotfound' : '';
    $output =  '<span><label for="curriculum_'.$curriculum_id.'" class="label'.$labelclass.'">';
    $output .= '<input type="checkbox"
                name="term_id[curriculum]['.$curriculum_id.']"
                class="niace_resource_searchtax curriculumcheck"
                data-resourcetype="curriculum" id="curriculum_'.$curriculum_id.'"
                value="'.$curriculum_id.'"/>' . $curriculum_name . "</label><br /></span>\n";
    return($output);
}


// outputTargetTerm - convert the targetterm array item into an input checkbox
function outputTargetTerm( $targetterm_name, $targetterm_id) {

    $labelclass = intval($targetterm_id) < 1 ? ' tagnotfound' : '';
    $output =  '<span><label for="targetterm_'.$targetterm_id.'" class="label'.$labelclass.'">';
    $output .= '<input type="checkbox"
                name="term_id[target]['.$targetterm_id.']"
                class="niace_resource_searchtax targetcheck"
                data-resourcetype="target" id="targetterm_'.$targetterm_id.'"
                value="'.$targetterm_id.'"/>' . $targetterm_name . "</label><br /></span>\n";
    return($output);
}


// termsToCheckboxes - convert a nested array of terms into a taxonomy checkbox fieldset
function termsToCheckboxes( $taxonomyTerms, $nestedArray, $outputFunc, $nested=0 ) {
    $output = "";
    foreach($nestedArray as $key=>$val) {
        //echo(str_repeat(" ", $nested * 4));
        if(is_array($val)) {
            // echo("HEADER " . $key . PHP_EOL);
            $output .= "<fieldset class=\"collapsible catform\"><legend style=\"margin-bottom: 0;\">".$key."</legend>";
            $output .= termsToCheckboxes( $taxonomyTerms, $val, $outputFunc, $nested + 1);
            $output .= "</fieldset>";
        } else {
            $matchId = matchTaxonomyTerm( $taxonomyTerms, $val );
            // echo($val . " (". $matchId . ")". PHP_EOL);
            $output .= $outputFunc( $val, $matchId);
        }
    }
    return($output);
}


// get_niace_resource_categories - create a checkbox list of terms to use for the tag cloud
function get_niace_resource_categories($unid = '', $searchable = "true"){
    require( WPRESOURCESMANAGERPATH . '/includes/swniace-resources-manager-initialisation_data.php' );
    $output ="";
    $curriculumTaxonomy = 'resource_curriculum_category'; //Choose the taxonomy
    $targetTaxonomy = 'resource_target_category'; //Choose the taxonomy
    $args = array(
        'orderby'       => 'none', // 'name',
        'order'         => 'ASC',
        'hide_empty'    => false, 
        'exclude'       => array(), 
        'exclude_tree'  => array(), 
        'include'       => array(),
        'number'        => '', 
        'fields'        => 'all', 
        'slug'          => '', 
        'parent'         => 0,
        'hierarchical'  => true, 
        'child_of'      => 0, 
        'get'           => '', 
        'name__like'    => '',
        'pad_counts'    => false, 
        'offset'        => '', 
        'search'        => '', 
        'cache_domain'  => 'core'
    );

    // added this style to catch terms not in the database, so it can be corrected.
    $output .=  "<style> .tagnotfound { color:red; } </style>\n";

    if( $searchable == true) {
        $output .= "<h3 id=\"personalised-cloud\" class=\"widget-title\">Create a personalised tag cloud</h3>";
    }

    $output .=  "<div class='panels_container'>\n";

    if( $searchable == true) {
        $output .= "<p>Select one or more Curricula <strong>and</strong> one or more Target items, then 'Create Cloud' to see a personalised tag cloud.</p>";
    }

    $output .= "<div class='resources_panel panel1'>\n";
    if( $searchable != true) {
        $output .= "<p>Tag your resources with one or more <strong>Curriculum Area</strong> tags.</p>";
    }
    $output .= "<h3>Curricula</h3>";
    $curricula = get_terms( $curriculumTaxonomy, $args);
    $output .= termsToCheckboxes( $curricula, $resource_curriculum_category, "outputCurriculumTerm");
    $output .=  "<div class='panel1ButtonPlaceHolder'></div>\n";
    $output .=  "</div>\n";

    $output .=  "<div class='resources_panel panel2'>";
    if( $searchable != true) {
        $output .= "<p>Add one or more <strong>Equality and Diversity</strong> audience tags.</p>";
    }
    $output .= "<h3>Target</h3>\n";
    $targets = get_terms( $targetTaxonomy, $args);
    $output .= termsToCheckboxes( $targets, $resource_target_category, "outputTargetTerm");
    $output .=  "<div class='panel2ButtonPlaceHolder'></div>\n";
    $output .=  "</div>\n";

    if($searchable == true) {
        $output .=  "<button id=\"create-personal-cloud\">Create cloud</button>\n";
        $output .=  "<div class='niace_searchcloud_container'></div>\n";
    }

    return($output);
}
?>