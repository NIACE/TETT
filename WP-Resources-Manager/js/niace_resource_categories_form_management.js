(function($) {

    $('#personalised-cloud').click(function(event) {
        event.preventDefault();
        $('.panels_container').toggle();
    });

//    $('.niace_resource_searchtax').click(function(){
    $('#create-personal-cloud').click(function(){
        event.preventDefault();
        var thestatus = {'curriculum':[],'target':[]};
        $('.niace_resource_searchtax').each(function(){
            var id=$(this).val().toString();
            var isChecked = $(this).attr('checked')?1:0;
            if(isChecked == 1){
                var resourcetype = $(this).data('resourcetype').toString();
                thestatus[resourcetype].push(id);
            }
        });
        // console.log(thestatus);
        var data = {
            action: 'show_hide_tagcloud_elements',
            terms: thestatus      // We pass php values differently!
        };
        console.log(data);
        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        $.post(ajaxobject.ajaxurl, data, function(response) {
            $('.niace_searchcloud_container').html(response);
        });
    });

    $( "#page-widgets h3" ).click( function() {
        $(this).toggleClass('collapsed');
        $(this).parent().children('a, p').slideToggle(200);
    });

    $('.collapsible a, .collapsible p').toggle();

//    $('fieldset.collapsible span').toggle();
    $('fieldset legend').click( function() {
        $(this).parent().children('span').toggle();
    });

    $('.panels_container').hide();
})(jQuery);
