function webobject_switch(button,object,id,action,action_nb,state){
	webobject = $(button).parent();

	$(button).attr("disabled",true);


	//If button green change state to ON
	if($(button).hasClass("btn-success")){
		newstate = "alert-success";
	}
	//If button is red change state to OFF
	else if($(button).hasClass("btn-danger")){
		newstate = "alert-danger";
	}
	//If button is orange change state 
	else if($(button).hasClass("btn-warning")){
		if(webobject.hasClass("alert-success")){
			newstate = "alert-danger";
		}	
		else{
			newstate = "alert-success";
		}
	}
	//If button is blue no change
	else if($(button).hasClass("btn-primary")){
		if(webobject.hasClass("alert-success")){
			newstate = "alert-success";
		}	
		else{
			newstate = "alert-danger";
		}
	}


	webobject.removeClass("alert-success alert-danger");
	webobject.addClass("alert-warning");

	$.ajax({
		url: "actions.php",
		data: {type: "action", object: object , id: id,action: action, action_nb: action_nb, state: state}
	}).done(function ( data ) {
		if(data == 0){
			webobject.removeClass("alert-warning");
			webobject.addClass(newstate);
		}
		else
		{ 
			ajax_notify(data);
		}
		$(button).attr("disabled",false);
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

