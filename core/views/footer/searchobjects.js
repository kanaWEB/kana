function search_ajax_objects(search_input){
	search = $(search_input).val();
	//console.log(search);
	$.ajax({
			url: "actions.php",
			dataType: "json",
			data: {type: "data", data: "searchobjects", search: search}
		}).done(function ( data ) {
			//console.log(data);
			if(data){
				//console.log(data)
				$(".objects_items").hide();
				for(var key in data){
					show_object(data[key]);
				}
			}
			else{
				$(".objects_items").show();
			}
		});
}

function show_all_objects(){
	$(".objects_items").show();
	$(".objects_item").removeClass("hide");
}


function hide_all_objects(){
	$(".objects_items").hide();
}

function show_object(object_name){
	$("#"+object_name).show();
	$("#"+object_name).removeClass("hide");
}
