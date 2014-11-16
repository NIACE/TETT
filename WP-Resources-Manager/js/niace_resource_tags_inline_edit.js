(function($) {
	// we create a copy of the WP inline edit term function
	var $wp_inline_edit = inlineEditTax.edit;
	// and then we overwrite the function with our own code
	inlineEditTax.edit = function( id ) {
		// "call" the original WP edit function
		// we don't want to leave WordPress hanging
		$wp_inline_edit.apply( this, arguments );

		// now we take care of our business

		// get the term ID
		var $term_id = 0;
		if ( typeof( id ) == 'object' )
			$term_id = parseInt( this.getId( id ) );

		if ( $term_id > 0 ) {
			// define the edit row
			var $edit_row = $( '#edit-' + $term_id );
			var $term_row = $( '#term-' + $term_id );
            var $niace_resource_tag =$( '#niace_resource_tag_' + $term_id );
			// get the data
			var $groupnameId = $niace_resource_tag.val();
			// populate the data
            $('#select_taxonomygrouping',$edit_row).val($groupnameId).prop('selected', true);
		}
	};

})(jQuery);