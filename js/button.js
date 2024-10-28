

jQuery(document).bind('gform_post_render', function(){ 
	setup_button_texts();
	setup_bct_clicks();
	
	jQuery( document ).on( 'change keyup', '.gfield input, .gfield select, .gfield textarea', function( event ) {
		setup_button_texts();
	});
	
	/*gform.addAction( 'gform_input_change', function( elem, formId, fieldId ) {
		setup_button_texts();
	}, 10 );*/
});

bcten = typeof bcten != "undefined" ? bcten : false;

function setup_button_texts() {
	
	if( typeof custom_button_data != "undefined" && custom_button_data && bcten) { 
		
		if( Array.isArray(custom_button_data) ) {
			
			//console.log(custom_button_data);
			
			jQuery.each(custom_button_data, function(index, custom_button_data_loop) {
				
				if( custom_button_data_loop && custom_button_data_loop.form_id ) { 
					
					//console.log(index);
					//console.log(custom_button_data_loop);
					
					var form_id = custom_button_data_loop.form_id;
					var text_data = custom_button_data_loop.text;
					
					if( text_data ) { 
					
						if( text_data.default ) { 	
							jQuery("#gform_submit_button_" + form_id).val(text_data.default);
						}
										
						jQuery.each(text_data, function(index, value) { 
							
							if(value) {
								
								if( value.bctcl ) {

									if( value.button_text ) {
									
										var field_action = gf_get_field_action( form_id, value.bctcl );
										
										if( field_action == "show" ) {
											
											jQuery("#gform_submit_button_" + form_id).val(value.button_text);
											
											return false;
										}
									}
									
								}
							}
						});
						
					}
				}
			});
		}
	}
}

function setup_bct_clicks() {
	
	jQuery( document ).on( 'submit.gravityforms', '.gform_wrapper form', function( event ) {
	
		var element = jQuery(this);
		var formWrapper = element.closest( '.gform_wrapper' );
		var formID = formWrapper.attr( 'id' ).split( '_' )[ 2 ];
		
		if(typeof custom_button_data != "undefined" && custom_button_data) { 
			
			if( Array.isArray(custom_button_data) ) {
				
				jQuery.each(custom_button_data, function(index, custom_button_data_loop) {
					
					if( custom_button_data_loop && custom_button_data_loop.form_id ) { 
						
						var form_id = custom_button_data_loop.form_id;
						var text_data = custom_button_data_loop.text;
						
						if( text_data ) { 
						
							if( text_data.default_click_text ) { 	
								jQuery("#gform_submit_button_" + form_id).val(text_data.default_click_text);
							}
							
							if(bcten) {
								jQuery.each(text_data, function(index, value) { 
									
									if(value) {
										
										if( value.bctcl ) {

											if( value.button_click_text ) {
											
												var field_action = gf_get_field_action( form_id, value.bctcl );
												
												if( field_action == "show" ) {
													
													jQuery("#gform_submit_button_" + form_id).val(value.button_click_text);
													
													return false;
												}
											}
											
										}
									}
								});
							}
						}
					}
				});
			}
		}
	});
		
}


function validate_null(value) {
	
	if( typeof value !== "undefined" && value && value != "" && value != 0 ) {
		
		return true;
	}
	
	return false;
}

