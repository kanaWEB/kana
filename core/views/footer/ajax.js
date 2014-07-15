disable_ajaxbuttons(false,false,true);
function ajax_play(){
	ajax_start()
	disable_ajaxbuttons(true,false,true);
	change_colorbuttons("#ajax_refresh","btn-danger","btn-default");
}

function ajax_refresh(){
	ajax_refresh_timer = setInterval(function(){
		ajax_start();
	},5000);
	disable_ajaxbuttons(true,true,false);
	change_colorbuttons("#ajax_refresh","btn-default","btn-danger");
}

function ajax_stop(){
	clearInterval(ajax_refresh_timer);
	change_colorbuttons("#ajax_refresh","btn-danger","btn-default");
	disable_ajaxbuttons(true,false,true);
}

function disable_ajaxbuttons(play,refresh,stop){
	$("#ajax_play").attr("disabled",play);
	$("#ajax_refresh").attr("disabled",refresh);
	$("#ajax_stop").attr("disabled",stop);
}

function change_colorbuttons(id,oldclass,newclass){
	$(id).removeClass(oldclass);
	$(id).addClass(newclass);
}

function ajax_start(){
	ajax_update("progressbar",".progress-bar");
	ajax_update("label","span");
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
			data: {action: "data", data: data_link , data_id: data_id}
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