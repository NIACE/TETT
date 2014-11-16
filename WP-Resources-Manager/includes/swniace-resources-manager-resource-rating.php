<?php

function niace_resource_ratings($postID){
    $current_ratings = get_post_meta($postID, 'niace_resource_rating', false);
    $average = get_post_meta($postID, 'niace_resource_rating_average', true);
    $average_check =$average;
    $ratingsblock = "<div id='containerrating_$postID' class='resource_rating_main_container'>\n";
    $ratingsblock .="<div id='averagerating_$postID' class='resource_rating_container'>Average rating ".round($average,2)."/5 <br />\n";
    $i=0;
    while($i<5){
        
        if($average_check>=0.75){
            $ratingsblock .="<div id='the_rating_$postID-$i' class='resource_rating_display niacerated_$i full'></div>\n";
        }
        elseif($average_check<=0.25){
            $ratingsblock .="<div id='the_rating_$postID-$i' class='resource_rating_display niacerated_$i empty'></div>\n";
        }
        else{
            $ratingsblock .="<div id='the_rating_$postID-$i' class='resource_rating_display niacerated_$i half'></div>\n";
        }
        $average_check=$average_check-1;
        $i++;
    }
    $ratingsblock .="</div>\n";
    $ratingsblock .="<div id='submitrating_$postID' class='resource_rating_container'><br />Your rating<br />\n";
    $ratingsblock .="<div id='submit_the_rating_$postID-0' class='resource_rating_submit niacerated_1 inactive' data-rating='1' data-post='$postID' ></div>\n";
    $ratingsblock .="<div id='submit_the_rating_$postID-1' class='resource_rating_submit niacerated_2 inactive' data-rating='2' data-post='$postID' ></div>\n";
    $ratingsblock .="<div id='submit_the_rating_$postID-2' class='resource_rating_submit niacerated_3 inactive' data-rating='3' data-post='$postID' ></div>\n";
    $ratingsblock .="<div id='submit_the_rating_$postID-3' class='resource_rating_submit niacerated_4 inactive' data-rating='4' data-post='$postID' ></div>\n";
    $ratingsblock .="<div id='submit_the_rating_$postID-4' class='resource_rating_submit niacerated_5 inactive' data-rating='5' data-post='$postID' ></div>\n";
    $ratingsblock .="</div>\n";   
    $ratingsblock .="</div>\n";
    return $ratingsblock;
}
?>
