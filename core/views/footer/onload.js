//check for notification and ajax request
$(document).ready(function(){
	//maj();
	if (typeof error !== 'undefined') {
		notification(error,"error");
	}
	if (typeof notice !== 'undefined') {
		notification(notice,"success");
	}


	//http://www.bootply.com/110686# by twlaam
//Image inside Select field
$('.selectpicker').selectpicker({
  width: '270px',
  style: 'btn btn-xs btn-default'
 });

});