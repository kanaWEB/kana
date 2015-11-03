//Toggle color of statebox
function state_toggle(button,color_class){
var data = prepare_state(button);

data.button.attr("disabled",true);
//Set new style
data.state_box.data("style","state_"+color_class);

//Toggle
data.state_box.removeClass(data.style);
data.state_box.addClass("state_"+color_class);

 webobject_switch(data.button);
}

//Blink the state box
function state_blink(button,color_class){
	var data = prepare_state(button);
	data.button.attr("disabled",true);
    console.log(data.state_box);

    webobject_switch(data.button);
	//Toggle
	data.state_box.removeClass(data.style);
	data.state_box.addClass("state_"+color_class);
	
	$(data.state_box).fadeTo(50, 0.1).fadeTo(50, 1.0);
	$(data.state_box).fadeTo(50, 0.1).fadeTo(50, 1.0, function(){
		data.state_box.removeClass("state_"+color_class);
	    data.state_box.addClass(data.style);
	});
}

//Fade in then out the statebox
function state_fadeinout(button,color_class){
	var effect = prepare_state(button);
	effect.button.attr("disabled",true);
    console.log(effect.state_box);

    webobject_switch(effect.button);
	//Toggle
	effect.state_box.removeClass(effect.style);
	effect.state_box.addClass("state_"+color_class);
	
	$(effect.state_box).fadeTo(1000, 0.1).fadeTo(1000, 1.0);
	$(effect.state_box).fadeTo(1000, 0.1).fadeTo(1000, 1.0, function(){
		effect.state_box.removeClass("state_"+color_class);
	    effect.state_box.addClass(effect.style);
	});
}

function state_opacity(button){
console.log("change opacity");
}

//Add an image to the state box
function state_img(button,data){
	console.log($(button));
	console.log(data);
	var effect = prepare_state(button);
	effect.button.attr("disabled",true);

	$.ajax({
			url: "actions.php",
			type: "POST",
			data: {type: "data", id: effect.button.data("id") , data: data}
		}).done(function ( img ) {
			effect.state_box.attr("style","height:100%;");
			effect.state_box.html('<img src="'+img+'" style="max-width: 100%;">');
			effect.button.attr("disabled",false);
		});
}

function button_link_htmlviews(button,url){
	var effect = prepare_state(button);
	effect.button.attr("disabled",true);
	command = effect.button.data("command");
	object = effect.button.data("object");
	id = effect.button.data("id");
	url = 'actions.php?type=htmlviews&plugin_name='+object+'&action_name='+command+'&page='+url+'&id='+id;
	//console.log(url);
	window.open(url);
}

//Go to a page
function button_link_data(button,data){
	var effect = prepare_state(button);
	effect.button.attr("disabled",true);

	$.ajax({
			url: "actions.php",
			type: "POST",
			data: {type: "data", id: effect.button.data("id") , data: data}
		}).done(function ( url ) {
			window.location.replace(url);
			effect.button.attr("disabled",false);
		});
}

function prepare_state(button){
  //Get button
  button = $(button);
  uid = button.data("uid");
  
  //Get state
  state_box = $("#state_"+uid);
  style = state_box.data("style");

  return {button: button,state_box: state_box, style: style };
}