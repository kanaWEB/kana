//Deprecated

disable_ajaxbuttons(false,false,true);
function ajax_play(){
	ajax_start();
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
	ajax_update("label2","span");
}


function ajax_update(view_type,view_obj){
	console.log("Search for" + "."+view_type+"-ajax");
	$("."+view_type+"-ajax").each(function(){
		data_link = $(this).attr("id");
		data_id = $(view_obj,this);
		data_id = $(data_id).attr("id");
		console.log("ID:"+data_id);		
		$.ajax({
			url: "actions.php",
			dataType: "json",
			data: {type: "data", data: data_link , data_id: data_id}
		}).done(function ( data ) {
			if(view_type == "progressbar"){
				update_progressbar(data);
			}

			if(view_type == "label"){
				update_label(data);
			}

			if(view_type == "label2"){
				update_2label(data);
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

//Manage 1,n label / label color
function update_label2(data){
	console.log(data);
	label1 = $("#" + data.data_id + "1");
	label2 = $("#" + data.data_id + "2");
	label1.removeClass();
	label1.addClass("label label-"+data[0].label);
	label2.addClass("label label-"+data[1].label);
	label1.html(data[0].data);
	label2.html(data[1].data);
}

