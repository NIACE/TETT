jQuery(window).load(function(){
    jQuery("label").find('[data-setting="caption"]').hide();
	jQuery('.fieldcontainer').hide();
	jQuery('#resource_type').change(function(){
		//alert('Value change to ' + jQuery(this).attr('value'));
		//niace_submissionformmanagementFields(jQuery(this).attr('value'));
        var selectedoption = jQuery(this).attr('value');
        if ( fieldsoptions.hasOwnProperty(selectedoption) ) {
			jQuery('.fieldcontainer').hide();
			jQuery('.'+selectedoption).show();
            jQuery('.all').show();
        }
        else{
            jQuery('.fieldcontainer').hide();
        }
	});
    
    jQuery("#niace_resources_form").submit(function(event){
        //check level1 checked
        var deny1=true;
        var deny2=true;
        var deny3=true;
        var allowfields=true;
        if(return_niace_description() == ''){
            allowfields=false;
        }
        if(jQuery(".fieldcontainer>#title").val() == ''){
            allowfields=false;
        }
        if(jQuery(".fieldcontainer>#resource_description").val() == ''){
            allowfields=false;
        }
 
        jQuery('.curriculumcheck').each(function(index, value) { 
            if(jQuery(this).prop('checked')){
                deny1=false;
            }
        });
        jQuery('.targetcheck').each(function(index, value) { 
            if(jQuery(this).prop('checked')){
                deny2=false;
            }
        });
        if(jQuery('#additionaltags').val() !=''){
            deny3=false;
        }

        if(!allowfields){
            event.preventDefault();
            alert("You *must* fill in required (*) fields");
        }
        if(deny1||deny2||deny3){
            event.preventDefault();
            alert("You *must* tag the resource at level 1 and 2 of the category hierarchy AND supply a personalised tag");
        }
    });
});