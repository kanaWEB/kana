//Manage Sensors Modifications

function sensor_change_type(field){
	id = $(field).attr("id");
	field_type = "text";
	value = $(field).val();

	$.ajax({
			url: "settings.php",
			dataType: "json",
			data: {category: "sensors", menu: "allsensors" ,sensor_id: id , sensor_newtype: value, submit: true}
		}).done(function ( data ) {
			console.log(data);
			quicknoty(data.message)
		});
}

function sensor_change_name(field){
	id = $(field).attr("id");
	console.log(id);
	value = $(field).prev().children().val();
	
	$.ajax({
			url: "settings.php",
			dataType: "json",
			data: {category: "sensors", menu: "allsensors" ,sensor_id: id , sensor_newname: value, submit: true}
		}).done(function ( data ) {
			console.log(data);
			quicknoty(data.message)
		});

}

function sensor_change_save(field){
	id = $($(field).parent().parent().parent().children()[0]).attr("id");
	value = $(field).is(':checked');
	if(value){
		value = 1;
	}else{
		value = 0;
	}

	console.log(value);

	$.ajax({
			url: "settings.php",
			dataType: "json",
			data: {category: "sensors", menu: "allsensors" ,sensor_id: id , sensor_newsave: value, submit: true}
		}).done(function ( data ) {
			console.log(data);
			quicknoty(data.message)
		});

}

function sensor_delete(field){
	console.log("DELETED SENSORS");
}