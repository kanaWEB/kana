function autodetect(button,collector,object){
$(button).attr("disabled",true);
$(button).removeClass("btn-primary");
$(button).addClass("btn-danger");
input = $(button).next().next().next();
input.val("");

data=object+"/lastcode";

$.ajax({
			url: "actions.php",
			data: {type: "data", data:data, data_id:"true"}
		}).done(function ( data ) {
			input.val(data);
			$(button).attr("disabled",false);
			$(button).removeClass("btn-danger");
			$(button).addClass("btn-primary");
});

}