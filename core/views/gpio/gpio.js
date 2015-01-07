function is_int(value){ 
	if((parseFloat(value) == parseInt(value)) && !isNaN(value)){
		return true;
	} else { 
		return false;
	} 
}

function rotate(deg){
	$(".rotable").css("-ms-transform","rotate("+deg+"deg)");
	$(".rotable").css("-webkit-transform","rotate("+deg+"deg)");
	$(".rotable").css("transform","rotate("+deg+"deg)");
	if(deg == 180){
		norotate(deg);
	}
	if(deg == 0){
		norotate(deg);
	}
}

function norotate(deg){
	$(".no-rotate").css("-ms-transform","rotate("+deg+"deg)");
	$(".no-rotate").css("-webkit-transform","rotate("+deg+"deg)");
	$(".no-rotate").css("transform","rotate("+deg+"deg)");
}

function checkgpio(){
	$.ajax({
		url: "actions.php",
		dataType: "json",
		data: {type: "data", data: "gpio/pins" , data_id: "gpios"}
	}).done(function ( data ) {
		console.log("Check GPIO");
		data = data.data
			//console.log(data)
			for(var key in data){
				if(data.hasOwnProperty(key)){
					var obj = data[key];
					pin = obj.wPi;
					
					if(is_int(pin)){
						state = obj.Value;
						gpio_id = "#gpio_"+pin;
						id = $(gpio_id);
						id.html(state);
						if(obj.V){
							id.removeClass("label-warning");
							id.addClass("label-success");
						}
						else{
							id.removeClass("label-success");
							id.addClass("label-warning");
						}			
					}
				}
			}
		});
}

function checkloopgpio(button){
	console.log("AJAX TIMER : Gpio checker (core/views/gpio/gpio.js)")
	button = $(button);
	if(button.hasClass("btn-success")){
		console.log("Play gpio");
		button.removeClass("fa-play-circle-o");
		button.removeClass("btn-success");
		button.addClass("fa fa-stop btn-danger")
		gpio_timer = setInterval(function(){
			checkgpio();
		},500);
	}
	else{
		console.log("Stop gpio");
		button.removeClass("fa-stop");
		button.removeClass("btn-danger");
		button.addClass("fa-play-circle-o btn-success");
		clearInterval(gpio_timer);
	}
}
