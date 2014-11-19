function webobject_switch(button,object,id,action,action_nb,state){
	webobject = $(button).parent().parent();
	console.log($(button));
	console.log(webobject);
	$(button).attr("disabled",true);


	//If button green change state to ON
	if($(button).hasClass("btn-success")){
		newstate = "panel-success";
	}
	//If button is red change state to OFF
	else if($(button).hasClass("btn-danger")){
		newstate = "panel-danger";
	}
	//If button is orange change state 
	else if($(button).hasClass("btn-warning")){
		if(webobject.hasClass("panel-success")){
			newstate = "panel-danger";
		}	
		else{
			newstate = "panel-success";
		}
	}
	//If button is blue no change
	else if($(button).hasClass("btn-primary")){
		if(webobject.hasClass("panel-success")){
			newstate = "panel-success";
		}	
		else{
			newstate = "panel-danger";
		}
	}


	webobject.removeClass("panel-success panel-danger");
	webobject.addClass("panel-warning");

	$.ajax({
		url: "actions.php",
		data: {type: "action", object: object , id: id,action: action, action_nb: action_nb, state: state}
	}).done(function ( data ) {
		if(data == 0){
			webobject.removeClass("panel-warning");
			webobject.addClass(newstate);
		}
		else
		{ 
			ajax_notify(data,"top");
		}
		$(button).attr("disabled",false);
	});
}

function webobject_data(button,id,data){
	console.log($(button));
	data_id = $(button).parent().parent().find("#data");
	$.ajax({
			url: "actions.php",
			data: {type: "data", id: id , data: data}
		}).done(function ( data ) {
			data_id.attr('src',"data:image/jpeg;base64,"+data);
		});
}



function change_gpio(button,gpio){
	
	if($(button).hasClass("label-success")){
		$.ajax({
			url: "actions.php",
			data: {type: "gpio", gpio: gpio , state: 0}
		}).done(function ( data ) {
			$(button).removeClass("label-success");
			$(button).html("Low");
			$(button).addClass("label-warning");
		});
	}

	if($(button).hasClass("label-warning")){
		$.ajax({
			url: "actions.php",
			data: {type: "gpio", gpio: gpio , state: 1}
		}).done(function ( data ) {
			$(button).removeClass("label-warning");
			$(button).html("High");
			$(button).addClass("label-success");
		});
	}
}

