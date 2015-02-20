/*

@todo : Import only if use

*/
ajax_refresh_speed = 5000;


if($(".ajax-sensor").length){
	console.log("Detected sensors");
	console.log("AJAX TIMER: Sensors core/views/footer/data.js");
	ajax_refresh_sensor_timer = setInterval(function(){
		ajax_refresh_sensor();
	},ajax_refresh_speed);
}

function ajax_refresh_sensor(){
	$(".ajax-sensor").each(function(){
		data_id = $(this).attr("id");
		data_link = "sensors/dbdata";
	});

	$.ajax({
		url: "actions.php",
		dataType: "json",
		data: {type: "data", data: data_link , data_id: data_id}
	}).done(function ( data ) {
		widget = $("#" + data.data_id);

		if(data.data == 0){
			$(widget).children().removeClass();
			$(widget).children().addClass("panel panel-success");
		}
		if(data.data == 1){
			$(widget).children().removeClass();
			$(widget).children().addClass("panel panel-danger");
		}
	});
}




//Disable Stop button only
disable_ajaxbuttons(false,false,true);

//Launch ajax and change buttons
function ajax_play(){
	ajax_start()
	disable_ajaxbuttons(true,false,true);
}

//Refresh ajax every 5 seconds @todo timer should be modifiable
function ajax_refresh(){
	console.log("AJAX TIMER : Data checker (core/views/footer/data.js)");
	ajax_start();
	//Start timer
	ajax_refresh_timer = setInterval(function(){
		ajax_start();
	},ajax_refresh_speed);

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

function refresh_datalist(){
	$("#button_datalist").removeClass();
	$("#button_datalist").addClass("btn btn-danger");
	$("#button_datalist").attr("disabled",true);
	$(".datafile_td").remove();

	//AJAX	
	$.ajax({
		url: "actions.php",
		dataType: "json",
		data: {type: "data", data: "data/datafile" , display: "json"}
	}).done(function ( datafiles ) {
		//console.log(datafiles);
		table = $("#datafile_table");
		total_args = 0;
		row_id = 0;
		for (var datafile in datafiles) {
			if (datafiles.hasOwnProperty(datafile)) {
				row_id++;
				html_code = "";
				
				//Icons
				if(datafiles[datafile].icon != undefined){
					icon_html = '<img src="'+datafiles[datafile].icon+'">';
				}
				else{
					icon_html = '';
				}

				html_code = html_code + add_data_tr_open();
				html_code = html_code + add_data_td(icon_html);
				html_code = html_code + add_data_td(datafiles[datafile].name);
				html_code = html_code + add_data_td(datafiles[datafile].example);
				
				//Arguments
				args_nb = datafiles[datafile].args_nb
				max_args = 2;
				//if there is argument
				if(args_nb != 0){
					args = datafiles[datafile].args
					for(i = 1; i <= args_nb;i++){
						max_args = max_args - 1;
					//console.log(args);
					arg_obj = JSON.stringify(args[i]);
					html_code = html_code + add_modifier_td(i,row_id);
					inputs_html_parser(arg_obj,row_id,i);
				}

			}

			//Populate empty arguments
			for(i = 0; i < max_args; i++){
				html_code = html_code + add_data_td("");
			}

			html_code = html_code + add_magickey_td(datafile,row_id);
			html_code = html_code + add_data_tr_close();
			table.append(html_code);
		}
	}
	$("#button_datalist").removeClass();
	$("#button_datalist").addClass("btn btn-warning");
	$("#button_datalist").attr("disabled",false);

})
.fail(function(){
	$("#button_datalist").removeClass();
	$("#button_datalist").addClass("btn");
	$("#button_datalist").attr("disabled",false);
	alert("ERROR DATA");
});

}

function add_data_td(html_code){
	return '<td>'+html_code+'</td>';
}

function add_modifier_td(id,row_id){
	return '<td id="row'+row_id+'_arg_data_ajax'+id+'"></td>';
}

function add_magickey_td(html_code,row_id){
	return '<td><label id="magickey_'+row_id+'" class="label label-success">{"data":"'+html_code+'"}</td>'
}

function add_data_tr_open(){
	return '<tr class="datafile_td">';
}

function add_data_tr_close(){
	return '</tr>';
}

function inputs_html_parser(input,row_id,id){
	
	$.ajax({
		url: "actions.php",
		dataType: "json",
		data: {type: "data", data: "html/input", input: input,row_id:row_id,data_id: id}
	}).done(function ( json ) {
		console.log(json);
		id = "#row"+json.row_id+"_arg_data_ajax"+json.data_id
		$(id).html(json.html);
	});
}

function change_magickey(row_id,input){
	magickey = $("#magickey_"+row_id);
	magickey_json = magickey.html();
	magickey_obj = $.parseJSON(magickey_json);

	input = $(input);
	input_name = input.attr("name"); 
	input_val = input.val();

	magickey_obj[input_name] = input_val;
	console.log(magickey_obj);
	magickey.html(JSON.stringify(magickey_obj));
}

/* Weather data from yahoo */
//@todo Move to his own file

//Query woeid on yahoo api
function query_woeid(button){
	weather_place = $(button).prev().val();


	button_class = $(".weather_query");
	button_class.addClass("btn-warning");

	//weather_place = $("input:text[name=weather_place]").val();
	url = "http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20geo.places%20where%20text%3D%22" + encodeURI(weather_place) + "%22&format=json" ;
	$.getJSON( url, {
		tags: "woeid",
		tagmode: "any",
		format: "json"
	})
	.done(function( data ) {
		button_class = $(".weather_query");
		button_class.removeClass("btn-warning btn-danger");
		if (data["query"]["results"] != undefined){
			console.log("Woeid founded");

			button_class.addClass("btn-success");
			//console.log(data);
			
			var place = data["query"]["results"]["place"][0];
			if(place == undefined){
				var place = data["query"]["results"]["place"];
			}
			woeid = place["woeid"];
			town = place["name"];

			if(place["country"]["content"] != undefined){
			country = place["country"]["content"];
			}
			else
			{
				country = "";
			}

			if(place["admin1"] != undefined){
				region = place["admin1"]["content"];
			}
			else
			{
				region = "";
			}

			$("input[name=weather_place]").val(town + " " + region + " " + country);
			$("input[name=woeid]").val(woeid);
			//console.log($("input[name=woeid]"));
		}
		else{
			button_class.addClass("btn-danger");
			$("input[name=woeid]").val("??");
		}
	})
	.fail(function(){
		console.log("I can't retrieve woeid!")
		button_class = $(".weather_query");
		button_class.removeClass("btn-warning");
		button_class.addClass("btn-danger");
		$("input[name=woeid]").val("??");

	});
}

