//check for notification and ajax request
$(document).ready(function(){
	if (typeof error !== 'undefined') {
		notification(error,"error");
	}
	if (typeof notice !== 'undefined') {
		notification(notice,"success");
	}
});

//@todo Move elsewhere then onload.js

//http://www.bootply.com/110686# by twlaam
//Image inside Select field 
//Used mainly for actions groups and scenario
//@todo --> selectpicker.js should be only load when there is a selectpicker
$('.selectpicker').selectpicker({
  style: 'label-info label'
 });

/*
// Masonry manages widgets so it occupied the entire screen
//@todo --> auto_organize.js should be load only when there are widgets (views?)
$('#widgets').masonry({

  itemSelector: '.widget'
});
*/
//Organize
/*
function reload_packery(){
console.log("RELOAD PACKERY");
var $container = $('#widgets').packery({
  gutter:1,
  itemSelector:".widget"
});

$container.packery('bindResize')

}

*/



/*
//Draggable
$container.find('.widget').each( function( i, itemElem ) {
  // make element draggable with Draggabilly
  var draggie = new Draggabilly( itemElem,{
  	grid: [ 50, 50 ]
  } );
  // bind Draggabilly events to Packery
  $container.packery( 'bindDraggabillyEvents', draggie );
});
*/



//Generate auto resizable inputs
function resizeInput(text_field) {
	console.log($(text_field).attr('min-width'));
	console.log($(text_field).val().length);
    $(text_field).attr('style', "min-width:"+($(text_field).val().length * 5 + 10)+"px;");
}

function switchwidget(firstwidget,secondwidget){
	//console.log("Switching widget");

	//console.log("Get widgets")
	firstwidget = $("#"+firstwidget);
	secondwidget = $("#"+secondwidget);
	//console.log($(firstwidget));
	//console.log($(secondwidget));

	html_firstwidget = $(firstwidget).html();
	html_secondwidget = $(secondwidget).html();


	$(firstwidget).html(html_secondwidget);
	$(secondwidget).html(html_firstwidget);
	//console.log($(firstwidget));
	//console.log($(secondwidget));
	//reload_packery();
}

//@todo Refactor
widget_ismoving = false;
first_widget_moving = false;
second_widget_moving = false;
first_widget_button = false;


function switch_widget_clicked(widget_button){
	widget_button = $(widget_button);
	widget_button_id = widget_button.attr("id");
	widget_id = "widget-"+widget_button_id;
	//console.log(widget_id);

	if(widget_ismoving){

	console.log("Moved");
	second_widget_moving = widget_id;

	//console.log($("#"+first_widget_button));
	$("#"+first_widget_button).removeClass("btn-danger");
	$("#"+first_widget_button).addClass("btn-primary");

	switchwidget(first_widget_moving,second_widget_moving);
    
    $("#"+first_widget_button).attr("id",widget_button_id);
    $("#"+widget_button_id).attr("id",first_widget_button);
    $("#"+first_widget_moving).attr("id",second_widget_moving);
    $("#"+second_widget_moving).attr("id",first_widget_moving);

	widget_ismoving = false;
	first_widget_moving = false;
	second_widget_moving = false;
	first_widget_button = false;
	}
	else
	{
	console.log("Selected");
	first_widget_moving = widget_id;
	first_widget_button = widget_button_id;
	widget_button.removeClass("btn-primary");
	widget_button.addClass("btn-danger");
	widget_ismoving = true;
	}

}



//Generate Tooltips
$('.tooltip-js').tooltip();

//Manage ajaxswitch (used mainly for permissions)
$(".ajaxswitch").bootstrapSwitch();

//--> Move to view.js Should be only load when views
//Make a view a default view for user
function makeasdefault_view(button,id_view,id_user){
$(button).removeClass("btn-primary");
$(button).addClass("btn-success");
$(button).attr("disabled",true);
	$.ajax({
			url: "actions.php",
			data: {type: "defaultview", id_user: id_user, id_view: id_view}
		}).done(function ( data ) {
			ajax_notify(data,"top");
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
			ajax_notify(data,"top");
		});
}

//Delete a field with ajax
//@todo move elsewhere , Should only be load when a dbtable is load
function ajax_delete(button,url){
	console.log($(button));
	$(button).attr("disabled",true);
	$.ajax({
		url: url
	}).done(function(data){
		console.log(data);
		json = ajax_notify(data,"top");
		console.log(json.data);
		if(json.data !== false){
			console.log($("#del"+json.data));
			$("#del"+json.data).parent().parent().fadeOut( "slow");
		} 
	});

}
