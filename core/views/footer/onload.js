//check for notification and ajax request
$(document).ready(function(){
	//maj();
	if (typeof error !== 'undefined') {
		notification(error,"error");
	}
	if (typeof notice !== 'undefined') {
		notification(notice,"success");
	}


//@todo Move elsewhere form.js

//http://www.bootply.com/110686# by twlaam
//Image inside Select field 

$('.selectpicker').selectpicker({
  width: '270px',
  style: 'label-info label'
 });

});


$(".ajaxswitch").bootstrapSwitch();

//--> Move to default.js
function makeasdefault_view(button,id_view,id_user){
$(button).removeClass("btn-primary");
$(button).addClass("btn-success");
$(button).attr("disabled",true);
	$.ajax({
			url: "actions.php",
			data: {type: "defaultview", id_user: id_user, id_view: id_view}
		}).done(function ( data ) {
			ajax_notify(data);
		});
}

function makeasdefault_group(button,id_group,id_user){
$(button).removeClass("btn-primary");
$(button).addClass("btn-success");
$(button).attr("disabled",true);
	$.ajax({
			url: "actions.php",
			data: {type: "defaultgroup", id_user: id_user, id_group: id_group}
		}).done(function ( data ) {
			ajax_notify(data);
		});
}


