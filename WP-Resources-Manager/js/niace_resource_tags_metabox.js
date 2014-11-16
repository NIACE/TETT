(function($) {
    $('#newniace_resource_categories_parent option').prop('disabled',true);
    $('#newniace_resource_categories_parent .level-1').prop('disabled',false);
    $('#niace_resource_categories-add-submit').hide();
    $('#newniace_resource_categories').hide();
    $('#niace_resource_categories-add').prepend("<em>You can only add new categories under existing Level 2 categories. Please select an appropriate Level 2 category to proceed</em><br />")
    $('#newniace_resource_categories_parent').change(function(){
        if($("#newniace_resource_categories_parent option:selected").hasClass("level-1")){
            $('#niace_resource_categories-add-submit').show();
            $('#newniace_resource_categories').show();
        }
    });
})(jQuery);