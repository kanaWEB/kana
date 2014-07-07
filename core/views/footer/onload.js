//check for notification and ajax request
$(document).ready(function(){
	//maj();
	if (typeof error !== 'undefined') {
		notification(error,"error");
	}
	if (typeof notice !== 'undefined') {
		notification(notice,"success");
	}
/*
setInterval(function(){
if ( $( ".ajax_onload" ).length ) {
		console.log("AJAX ONLOAD----------------")
		ajax_onload("progressbar",".progress-bar");
		ajax_onload("label","span");
}
},1000);
*/
});
