function ajax_play(){
	ajax_update("progressbar",".progress-bar");
	ajax_update("label","span");
}

function ajax_refresh(){
ajax_refresh = setInterval(function(){
ajax_play();
},5000);
$("#ajax_play").attr("disabled",true);
$("#ajax_refresh").attr("disabled",true);
}

function ajax_stop(){
clearInterval(ajax_refresh);
$("#ajax_play").attr("disabled",false);
$("#ajax_refresh").attr("disabled",false);
}

function ajax_update(view_type,view_obj){
	$("."+view_type+"-ajax").each(function(){
		data_link = $(this).attr("id");
		data_id = $(view_obj,this);
		data_id = $(data_id).attr("id");
		console.log(data_id);		
		$.ajax({
			url: "actions.php",
			dataType: "json",
			data: {action: "ajax", data: data_link , data_id: data_id}
		}).done(function ( data ) {
			if(view_type == "progressbar"){
				update_progressbar(data);
			}

			if(view_type == "label"){
				update_label(data);
			}
		});
	});
}

function update_progressbar(data){
	console.log(data);

	progressbar = $("#" + data.data_id);
	progressbar.removeClass();
	progressbar.addClass("progress-bar progress-bar-"+data.label);
	progressbar.attr("aria_valuenow",data.data);
	progressbar.css("width",data.data + "%");
	progressbar.html("<span>"+data.data + "%</span>");

}

function update_label(data){
	console.log(data);
	label = $("#" + data.data_id);
	label.removeClass();
	label.addClass("label label-"+data.label);
	label.html(data.data);
}