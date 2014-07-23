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

function rotate(deg){
	$(".rotable").css("-ms-transform","rotate("+deg+"deg)");
	$(".rotable").css("-webkit-transform","rotate("+deg+"deg)");
	$(".rotable").css("transform","rotate("+deg+"deg)");
}