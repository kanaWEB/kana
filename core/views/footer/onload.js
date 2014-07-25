//check for notification and ajax request
$(document).ready(function(){
	//maj();
	if (typeof error !== 'undefined') {
		notification(error,"error");
	}
	if (typeof notice !== 'undefined') {
		notification(notice,"success");
	}

});
