<?php

$resource_curriculum_category = array(
    "Subject area" => array(
        "Agriculture, horticulture and animal care",
        "Business, administration and law",
        "Community development",
        "Construction, planning and the built environment",
        "Education and training",
        "Employment and Employability",
        "Engineering and manufacturing technologies",
        "Family learning",
        "Health, public services and care",
        "Humanities",
        "Information and communication technology",
        "Languages, literature and culture",
        "Leisure, travel and tourism",
        "Preparation for life and work",
        "Retail and commercial enterprise",
        "Science and mathematics",
        "Social sciences",
        "Non-specific curriculum subject area"
    ),
    "Type" => array(
        "Teaching",
        "Learning",
        "Assessment",
        "Continual Professional Development",
        "Ideas to inspire you"
    ),
    "Level" => array(
        "Pre-Entry",
        "Entry 1-3",
        "Level 1",
        "Level 2",
        "Level 3",
        "Level 4",
        "Level 5",
        "Level 6",
        "Level 7",
        "Traineeship",
        "Apprenticeship"
    )
);

$resource_target_category = array(
    "Equality and Diversity" => array(
        "Age",
        "Disability",
        "Gender identity",
        "Marriage and civil partnerships",
        "Pregnancy and maternity",
        "Race",
        "Religion and belief",
        "Sex",
        "Sexual orientation",
        "Other vulnerable groups",
        "Pan-Equalities"
    ),
    "Guidance/codes of practice" => array(
        "Guidance and codes of practice on equalities related legislation",
        "Legislation"
    ),
    "Differentiation and Accessibility – people’s access needs" => array(
        "Difficulties hearing" => array (
            "Deaf – British Sign Language",
            "Deaf – Hard of hearing",
            "Difficulties hearing",
            "Deaf – Partially Sighted Learner",
            "Deaf – Sign Supported English",
            "Deaf – Subtitles"
        ),
        "Difficulties learning and understanding" => array(
            "Auditory processing",
            "Dyscalculia",
            "Dyslexia",
            "Dyspraxia",
            "Handwriting difficulties",
            "Makaton",
            "Phonology difficulties",
            "Plain English",
            "Structure and sequencing",
            "Visual processing"
        ),
        "Difficulties with mobility and co-ordination" => array(
            "Manual dexterity difficulties",
            "Mobility difficulties",
            "Speech difficulties",
            "Handwriting difficulties"
        ),
        "Difficulties concentrating and remembering" => array(
            "Dementia",
            "Long term memory",
            "Short term and working memory"
        ),
        "Difficulties seeing" => array(
            "Blind partially sighted – Braille",
            "Blind partially sighted – large print",
            "Blind/partially sighted – screen reader"
        ),
        "Difficulties socialising" => array(
            "Intrusive thoughts",
            "Phobias",
            "Social anxiety"
        ),
        "Other differentiation" => array(
            "Asperger’s",
            "Autism",
            "Complex difficulties",
            "Dual sensory loss",
            "Multiple difficulties",
            "Other differentiation"
        )
    )
);

/*
$test = 0;
if($test) {

    function print_array( $myArray, $spaces=0 ) {
        foreach( $myArray as $key=>$val) {
            echo ( str_repeat( "&nbsp;", $spaces) );
            if(! is_numeric($key) ) {
                echo ($key);
            }
            if(is_array($val)){
                echo("<br />");
                print_array($val, $spaces+4);
            } else {
                if( ! is_numeric($val)) {
                    echo ($val. PHP_EOL);
                }
                echo ("<br />");
            }
        }
    }

    print_array($resource_curriculum_category);
    print_array($resource_target_category);
}
*/
