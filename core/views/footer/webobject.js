function webobject_switch(button,object,id,action,arg1){
	webobject = $(button).parent();

	$(button).attr("disabled",true);

	if($(button).hasClass("btn-success")){
		console.log("on");
		newstate = "alert-success";
	}
	else if($(button).hasClass("btn-danger")){
		console.log("off");
		newstate = "alert-danger";
	}
	else if($(button).hasClass("btn-warning"))
	{
		if(webobject.hasClass("alert-success")){
			newstate = "alert-danger";
		}	
		else
		{
			newstate = "alert-success";
		}
	}

	webobject.removeClass("alert-success alert-danger");
	webobject.addClass("alert-warning");

	$.ajax({
		url: "actions.php",
		data: {type: "action", object: object , id: id,action: action, arg1: arg1}
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


