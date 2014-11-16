<?php
$resource_types = array(
    '0'=>'Online resource',
    '1'=>'Document',
    '2'=>'Video',
    '3'=>'Picture',
    '4'=>'Audio',
    '5'=>'Journal Article',
    '6'=>'Performance',
    '7'=>'Interview'
);

$metafields = array(
    'File *' =>array('type' => 'file', 'usedin'=>array(1,2,3,4,5,6,7)),
    'Link *' =>array('type' => 'text', 'usedin'=>array(0)),
    'Author' =>array('type' => 'text', 'usedin'=>array(1,2,3,4,5)),
    'Publisher' =>array('type' => 'text', 'usedin'=>array(0,1,2,3,4,5)),
    'Place' =>array('type' => 'text', 'usedin'=>array(1,2,3,4,5,6,7)),
    'Date' =>array('type' => 'date', 'usedin'=>array(0,1,2,3,4,5,6,7)),
    'City' =>array('type' => 'text', 'usedin'=>array(1,2,3,4,5,6,7)),
    'Region' =>array('type' => 'text', 'usedin'=>array(1,2,3,4,5,6,7)),
    'Interviewer' =>array('type' => 'text', 'usedin'=>array(7)),
    'Interviewee' =>array('type' => 'text', 'usedin'=>array(7)),
    'Institution' =>array('type' => 'text', 'usedin'=>array(6)),
    'Country' =>array('type' => 'text', 'usedin'=>array(6)),
    'Writer' =>array('type' => 'text', 'usedin'=>array(6)),
    'Theatre' =>array('type' => 'text', 'usedin'=>array(6))
);

?>