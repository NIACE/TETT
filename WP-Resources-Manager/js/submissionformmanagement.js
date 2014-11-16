(function($) {

	function getResourcePropertiesErrors() {
		var alertmessage = '';

		if($(".fieldcontainer>#title").val() == ''){
			alertmessage = alertmessage + "* Title is a required field.\n";
		}
		if(return_niace_description() == ''){
			alertmessage = alertmessage + "* Description is a required field.\n";
		}
		if($("#resource_type").val() === 'onlineresource'){
			if($(".fieldcontainer>#niace_resources_link").val() == ''){
				alertmessage = alertmessage + "* Resource Link is a required field.\n";
			}
		}
		else if($("#niace_resources_file").val() == ''){
			alertmessage = alertmessage + "* Resource File is a required field.\n";
		}
		return( alertmessage );
	}

	function hasCurriculumTags() {
		var retval = false;
		$('.curriculumcheck').each(function(index, value) {
			console.log($(this).prop('id') + " : " + $(this).prop('checked'));
			if($(this).prop('checked') == true){
				retval = true;
			}
		});
		return(retval);
	}

	function hasTargetTags() {
		var retval = false;
		$('.targetcheck').each(function(index, value) {
			if($(this).prop('checked')){
				retval = true;
			}
		});
		return(retval);
	}

	function hasUserTags() {
		return( ! ($('#additionaltags').val() == '') ) ;
	}


	$(window).load(function(){
		$("label").find('[data-setting="caption"]').hide();
		$('.fieldcontainer').hide();
		$('#resource_type').change(function(){
			//alert('Value change to ' + $(this).attr('value'));
			//niace_submissionformmanagementFields($(this).attr('value'));
			var selectedoption = $(this).attr('value');
			if ( fieldsoptions.hasOwnProperty(selectedoption) ) {
				$('.fieldcontainer').hide();
				$('.'+selectedoption).show();
				$('.all').show();
				$('.tagfields').hide();
			}
			else{
				$('.fieldcontainer').hide();
			}
		});


		$("#niace_resources_form").submit(function(event){
			//check level1 checked
			var deny1=true;
			var deny2=true;
			var deny3=true;
			var allowfields=true;
			var errorcount = 0;
			var alertmessage = "";
			if($(".fieldcontainer>#title").val() == ''){
				allowfields=false;
				alertmessage = alertmessage + "* Title is a required field.\n";
				errorcount++;
			}
			if(return_niace_description() == ''){
				allowfields=false;
				alertmessage = alertmessage + "* Description is a required field.\n";
				errorcount++;
			}
	
			if($("#resource_type").val() === 'onlineresource'){
				if($(".fieldcontainer>#niace_resources_link").val() == ''){
					allowfields=false;
					alertmessage = alertmessage + "* Resource Link is a required field.\n";
					errorcount++;
				}
			}
			else if($("#niace_resources_file").val() == ''){
				allowfields=false;
				alertmessage = alertmessage + "* Resource File is a required field.\n";
				errorcount++;
			}

			if( hasCurriculumTags() ) {
				deny1=false;
			}
			if(deny1){
				alertmessage = alertmessage + "* You must provide a Curriculum tag.\n";
				errorcount++;
			}

			if( hasTargetTags() ) {
				deny2 = false;
			}
			if(deny2){
				alertmessage = alertmessage + "* You must provide an Equality and Diversity audience tag.\n";
				errorcount++;
			}

			if( hasUserTags() ) {
				deny3=false;
			}
			if(deny3){
				alertmessage = alertmessage + "* You must provide at least one of your own tags.\n";
				errorcount++;
			}

			if(!allowfields || deny1 || deny2 || deny3){
				event.preventDefault();
				if(errorcount == 1){
					alert("There was a problem with the submission:\n\n" + alertmessage);
				}
				else{
					alert("There were problems with the submission:\n\n" + alertmessage);
				}            
			}
		});
	});

// at completion of the initial field set
	$('#fields-completed').click(function(event) {
		event.preventDefault();
		var errorstr = getResourcePropertiesErrors();
		if(errorstr != '') {
			alert(errorstr);
		} else {
			$('.mainfields').hide();
			$('.panel2').hide();
			$('#user-tags').hide();
			$('#tag-detail').show();
			$('.panel1').css('width', '98%');
			$('.panel1').show();
			$('html, body').animate({ scrollTop: 0 }, 0); // go to top of page
		}
	});

// at completion of Curricula tagging
	$('.panel1ButtonPlaceHolder')
		.append('<input type="button" value="Next">') // Create the element
		.click(function(event) {
			event.preventDefault();
			if( hasCurriculumTags() == false ) {
				 alert("Please select at least one Curricula tag");
			} else {
				$('.panel1').hide();
				$('#tag-instructions').hide();
				$('.panel2').css('width', '98%');
				$('.panel2').show();
				$('html, body').animate({ scrollTop: 0 }, 0); // go to top of page
			}
		});

// at completion of Target tagging
	$('.panel2ButtonPlaceHolder')
		.append('<input type="button" value="Next">') // Create the element
		.click(function(event) {
			event.preventDefault();
			if( hasTargetTags() == false) {
				alert("Please select at least one Target tag");
			} else {
				$('.panel2').hide();
				$('#user-tags').show();
				$('html, body').animate({ scrollTop: 0 }, 0); // go to top of page
			}
		});

})(jQuery);
	