
//Authorized user of the group where the object is in only
//See action.actions for more information 
function webobject_switch(button){
	/*
		command:
		object:
		id:
		uid:
		state:
	*/

	console.log(button);
	command = button.data("command");
	object = button.data("object");
	id = button.data("id");
	uid = button.data("uid");
	state = button.data("state");

	$.ajax({
		url: "actions.php",
		type: "POST",
		dataType:"json",
		data: {type: "action", object: object , id: id,command: command,state: state,webobject: true}
	}).done(function ( result ) {
		console.log(result);
		 state_box = $("#state_"+result.uid);
		 console.log(state_box);
		 $(".dashboard_debug").remove();
 		 state_box.append('<input class="dashboard_debug form-control" type="text" value="'+result.text+'">');
		/*
		if(data == 0){
			console.log(state);
			//webobject.removeClass("panel-warning");
			//webobject.addClass(newstate);
		}
		else{ 
			ajax_notify(data,"top");
		}
		*/
		button.attr("disabled",false);
	});
}

//Admin only see gpio.actions
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

