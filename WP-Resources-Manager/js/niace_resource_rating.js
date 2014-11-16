jQuery(document).ready(function(){
    var already_submitted = false;
    
    jQuery('.resource_rating_submit').hover(
        function(){
            var rating = jQuery( this ).data('rating');
            var post = jQuery( this ).data('post');
            //alert(jQuery( this ).attr('id'));
            var star = 0;
            while(star<rating){
                jQuery( '#submit_the_rating_' + post + '-' + star ).addClass('active').removeClass('inactive');
                star++;
            }
        },
        function(){
            jQuery( '.resource_rating_submit' ).addClass('inactive').removeClass('active');
        }
    );
        
    jQuery( '.resource_rating_submit' ).click(function() {
        if(already_submitted){
            alert("You cannot rate this resources twice")
        }
        else{
            var data = {
                action: 'resource_rating_submit',
                rating:jQuery( this ).data('rating'),
                postID:jQuery( this ).data('post'),
                already_submitted:already_submitted
            };
            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            jQuery.post(ajaxobject.ajaxurl, data, function(response) {
                alert( response );
                already_submitted = true;
            });
        }
    });
});