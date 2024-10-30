/**
 * @author CSSJockey
 * @website http://www.cssjockey.com
 */
var cjQuery = jQuery.noConflict();

cjQuery(document).ready(function(){
/** Show/Hide Form **/	
	cjQuery(".showform").click(function(){
	    cjQuery("#cjsubmitform").fadeIn(200);
	    cjQuery("#cjerror").fadeOut();
	    cjQuery("#cjresponse").fadeOut();
		cjQuery('#clbody').removeClass('clhidden').css({'opacity':'0.8','filter':'alpha(opacity=80)'})
	})
	cjQuery("#closeform").click(function(){
		cjQuery("#cjsubmitform").fadeOut(200);
		cjQuery('#clbody').addClass('clhidden').css({'opacity':'1','filter':'alpha(opacity=100)'})
	})
/*** Ajax requests via forms ***/
	cjQuery('.aform').submit(function(){
	    var url = cjQuery(this).attr('action');
	    var dataString = cjQuery('.aform').serialize();
	    var update = url.split("#")[1];

		var email = cjQuery("#clemail").val();
		var title = cjQuery("#cltitle").val();
		var furl = cjQuery("#clurl").val();
		var info = cjQuery("#clinfo").val();
		var emailFormat = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
		var answer = cjQuery("#clanswer").val();
	
		if (email == '' || title == '' || furl == '' || info == '') {
			cjQuery("#cjerror").fadeIn(200).html("Missing Required Fields!");
		}else if (email.search(emailFormat) == -1) {
			cjQuery("#cjerror").fadeIn(200).html("Invalid Email Address!");
		}else if (answer == "" || answer != 4) {
			cjQuery("#cjerror").fadeIn(200).html("Incorrect Answer!");
		}else {
			cjQuery.ajax({
				type: "POST",
				url: "" + url + "",
				data: dataString,
				success: function(response){
					cjQuery('#' + update + "").html(response);
				},
				complete: function(){
					cjQuery("#cjerror").fadeOut(200);
					cjQuery("#cjresponse").fadeIn(200);
					cjQuery("#cjsubmitform input[type='text'], #cjsubmitform textarea").val('');
				}
			});
		}
	    return false;
	})
})

function limitText(limitField, limitNum) {
    if (limitField.value.length > limitNum) {
        limitField.value = limitField.value.substring(0, limitNum);
    } 
}