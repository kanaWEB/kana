function token_user_change(select_input,id_token){
console.log("TOKEN USER CHANGE");
id_user_obj = $(select_input);
id_user = id_user_obj.val();
console.log("Change id_user: "+ id_user + " of token " + id_token);


	$.ajax({
			url: "actions.php",
			data: {type: "token", id_user: id_user ,id_token: id_token, action: "user_change"}
		}).done(function ( data ) {
			ajax_notify(data);
		});


}