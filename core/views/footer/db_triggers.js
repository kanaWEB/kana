//Manage Sensors Modifications

function triggers_change(id){
	if($("#trigger_state_"+id).is(":checked")){
		state = 1;
	}
	else
	{
		state = 0;
	}
	$.ajax({
			url: "actions.php",
			data: {type: "triggers",id: id , state: state}
		}).done(function ( data ) {
			console.log(data);
			ajax_notify(data);
		});
}
