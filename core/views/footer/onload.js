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
//Used mainly for actions groups and scenario
$('.selectpicker').selectpicker({
  width: '270px',
  style: 'label-info label'
 });

});

// Masonry manages widgets so it occupied the entire screen
$('#widgets').masonry({
  columnWidth: 200,
  itemSelector: '.item'
});

$('.tooltip-js').tooltip();



//Manage ajaxswitch (used mainly for permissions)
$(".ajaxswitch").bootstrapSwitch();


//--> Move to default.js
//Make a view a default view for user
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

//Make a group a default group for user
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

//@todo move elsewhere
//Delete a field with ajax
function ajax_delete(button,url){
	console.log($(button));
	$(button).attr("disabled",true);
	$.ajax({
		url: url
	}).done(function(data){
		console.log(data);
		json = ajax_notify(data);
		console.log(json.data);
		if(json.data != false){
			console.log($("#del"+json.data))
			$("#del"+json.data).parent().parent().fadeOut( "slow");
		} 
	});

}
