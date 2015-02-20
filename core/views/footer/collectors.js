function collector_changestate(button,object){
	button_id = $(button).attr('id');
	button_class = $(button).attr('class');
	change_label_color($(button),"info");
	$(button).html("?");

	switch(button_class){
		//Check state
		case "label label-warning":
		$.ajax({
			url: "actions.php",
			dataType: "json",
			data: {type: "data", data: "collectors/state" , object: object, data_id: button_id}
		}).done(function ( data ) {
			label = $("#" + data.data_id);
			label.html(data.data);
			change_label_color(label,data.label);

		});
		break;

		//Off
		case "label label-success":
		$.ajax({
			url: "actions.php",
			dataType: "json",
			data: {type: "actions", type: "collectors" , object: object, command: "stop" ,data_id: button_id}
		}).done(function ( data ) {
			console.log(data);
			label = $("#" + data.data_id);
			console.log(label);
			label.html(data.state);
			change_label_color(label,data.label);
			
			//label = $("#" + data.data_id);
			//label.html(data.data);
			//change_label_color(label,data.label);
		});
		break;

		//On
		case "label label-danger":
		$.ajax({
			url: "actions.php",
			dataType: "json",
			data: {type: "actions", type: "collectors" , object: object, command: "start" ,data_id: button_id}
		}).done(function ( data ) {
			console.log(data);
			label = $("#" + data.data_id);
			label.html(data.state);
			change_label_color(label,data.label);
			console.log(label);
			//label = $("#" + data.data_id);
			//label.html(data.data);
			//change_label_color(label,data.label);
		});
		break;
	}
}