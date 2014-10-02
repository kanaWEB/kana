/*

@todo : Import only if use

*/
ajax_refresh_speed = 10000;

//Disable Stop button only
disable_ajaxbuttons(false,false,true);

//Launch ajax and change buttons
function ajax_play(){
	ajax_start()
	disable_ajaxbuttons(true,false,true);
}

//Refresh ajax every 5 seconds @todo timer should be modifiable
function ajax_refresh(){
	ajax_start();
	//Start timer
	ajax_refresh_timer = setInterval(function(){
		ajax_start();
	},5000);

	//Change buttons
	disable_ajaxbuttons(true,true,false);

}

//Stop ajax refresh
function ajax_stop(){
	clearInterval(ajax_refresh_timer);

	disable_ajaxbuttons(false,false,true);
}

//Disable buttons
function disable_ajaxbuttons(play,refresh,stop){
	$("#ajax_play").attr("disabled",play);
	$("#ajax_refresh").attr("disabled",refresh);
	$("#ajax_stop").attr("disabled",stop);
}

//Change button class
function change_colorbuttons(id,oldclass,newclass){
	$(id).removeClass(oldclass);
	$(id).addClass(newclass);
}

//Start ajax update
function ajax_start(){
	ajax_update("progressbar",".progress-bar");
	ajax_update("label","span");
	ajax_update("label2","span");
}

//Update ajax field only
function ajax_update(view_type,view_obj){
	$("."+view_type+"-ajax").each(function(){

		//GETid
		data_link = $(this).attr("id");
		
		data_id = $(view_obj,this);
		data_id = $(data_id).attr("id");

		change_td_color(data_id,"primary");
		//LOG
		

		//AJAX	
		$.ajax({
			url: "actions.php",
			dataType: "json",
			data: {type: "data", data: data_link , data_id: data_id}
		}).done(function ( data ) {
			change_td_color(data.data_id,"");
			if(view_type == "progressbar"){
				update_progressbar(data);
			}

			if(view_type == "label"){
				update_label(data);
			}

			if(view_type == "label2"){
				update_label2(data);
			}
		});
	});
}

//Update a progressbar
function update_progressbar(data){
	console.log(data);
	progressbar = $("#" + data.data_id);
	change_progressbar_color(progressbar,data.label);

	progressbar.attr("aria_valuenow",data.data);
	progressbar.css("width",data.data + "%");
	progressbar.html("<span>"+data.data + "%</span>");
}

//Update a label
function update_label(data){
	console.log(data);
	label = $("#" + data.data_id);
	change_label_color(label,data.label);

	label.html(data.data);
}

//Manage 1,n label / label color
function update_label2(data){
	console.log(data);
	label1 = $("#" + data.data_id);
	label2 = $("#" + data.data_id + "2");
	label1.removeClass();
	label1.addClass("label label-"+data[0].label);
	label2.removeClass();
	label2.addClass("label label-"+data[1].label);
	label1.html(data[0].data + " " + data[0].value);
	label2.html(data[1].data + " " + data[1].value);
}

function change_label_color(label,color){
	label.removeClass();
	label.addClass("label label-"+color);
}

function change_progressbar_color(progressbar,color){
	progressbar.removeClass();
	progressbar.addClass("progress-bar progress-bar-"+color);
}

function change_td_color(id,color){
	console.log(id);
	td = $("#" + id);
	td = td.parent().parent().parent().parent().parent();
	td.removeClass();
	td.addClass("bg-"+color);
}

function rotate(deg){
	$(".rotable").css("-ms-transform","rotate("+deg+"deg)");
	$(".rotable").css("-webkit-transform","rotate("+deg+"deg)");
	$(".rotable").css("transform","rotate("+deg+"deg)");
	if(deg == 180){
		norotate(deg);
	}
	if(deg == 0){
		norotate(deg);
	}
}

function norotate(deg){
	$(".no-rotate").css("-ms-transform","rotate("+deg+"deg)");
	$(".no-rotate").css("-webkit-transform","rotate("+deg+"deg)");
	$(".no-rotate").css("transform","rotate("+deg+"deg)");
}

function refresh_datalist(){
	$("#button_datalist").removeClass();
	$("#button_datalist").addClass("btn btn-danger");

	$(".datafile_td").remove();

	//AJAX	
	$.ajax({
		url: "actions.php",
		dataType: "json",
		data: {type: "data", data: "datas/datafile" , display: "json"}
	}).done(function ( datafiles ) {
		table = $("#datafile_table");
		for (var datafile in datafiles) {
			if (datafiles.hasOwnProperty(datafile)) {
				if(datafiles[datafile].icon != undefined){
					icon_html = '<img src="'+datafiles[datafile].icon+'">';
				}
				else{
					icon_html = '';
				}
				table.append("<tr class='datafile_td'><td>"+icon_html+"</td><td>"+datafiles[datafile].name+"</td><td>"+datafiles[datafile].exemple+'</td><td><label class="label label-success">{"data":"'+datafile+'"}</label></td></tr>');
			}
		}
		$("#button_datalist").removeClass();
		$("#button_datalist").addClass("btn btn-warning");
		

	});
}

