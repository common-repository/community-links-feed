jQuery(document).ready(function(){

	jQuery('.fade').animate( { borderRightWidth:"1px" }, 1000).fadeOut(1000);

	jQuery(".pending").hover(function(){
		jQuery(this).find('.useraction').show();
	},function(){
		jQuery(this).find('.useraction').hide();
	})
	
		jQuery(".clhidden").hide();
		jQuery('th').click(function(){
			var cid = jQuery(this).attr("id");
			jQuery("tr."+cid).slideToggle(0);
		})

	/*** Ajax requests via links ***/
	jQuery('.ajaxlink').click(function(){
		var url = jQuery(this).attr('href');
		var update = url.split("#")[1];
	    jQuery.ajax({
	        type: "POST",
	        url: "" + url + "",
	        data: "",
	        success: function(res){
	            jQuery('#' + update + "").fadeOut(200);
	        }
	    });
		return false;	
	})

	/*** Ajax requests via links ***/
	jQuery('.removelinks').click(function(){
		var url = jQuery(this).attr('href');
		var update = url.split("#")[1];
	    jQuery.ajax({
	        type: "POST",
	        url: "" + url + "",
	        data: "",
	        success: function(res){
				jQuery('#' + update + "").html(res);
	        }
	    });
		return false;	
	})

})

