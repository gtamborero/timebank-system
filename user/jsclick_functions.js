    
    jQuery(document).ready(function(){
		jQuery('div.rateit, span.rateit').rateit(); 
		
		// Set thickbox visible data when click on VALIDATE (accept / reject)
        jQuery('.thickbox.validate').click(function(){ 
            jQuery("#validateresult").html(''); 
            jQuery("#commentresult").html(''); 
			exchangeId = jQuery(this).attr('id');
			console.log ("inside");
            jQuery('user_value').html(jQuery ('#user_value' + exchangeId).html());
            jQuery('concept_value').html(jQuery ('#concept_value' + exchangeId).html());
            jQuery('amount_value').html(jQuery ('#amount_value' + exchangeId).html());
        });	
		
		// Set thichobox visible data when click on COMMENT
        jQuery('.comment').click(function(){ 
            jQuery("#commentresult").html(''); 
            jQuery("#comment2").val('');
            exchangeId = jQuery(this).attr('id');
        });
		
	});
