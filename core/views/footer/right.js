//@todo Move elsewhere right.js
function change_group_right(ajaxswitch,id_user,id_group){
state = $(ajaxswitch).is(':checked');

	$.ajax({
			url: "actions.php",
			data: {type: "groupright", id_user: id_user, id_group: id_group, state: state}
		}).done(function ( data ) {
			ajax_notify(data,"top");
		});
}

//@todo Move elsewhere right.js
function change_view_right(ajaxswitch,id_user,id_view){
state = $(ajaxswitch).is(':checked');

	$.ajax({
			url: "actions.php",
			data: {type: "viewright", id_user: id_user, id_view: id_view, state: state}
		}).done(function ( data ) {
			ajax_notify(data,"top");
		});
}