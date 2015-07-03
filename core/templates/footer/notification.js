tab_notification = [];
notify_time = 5000;
notify_closeWith = 'click';

$.noty.defaults = {
	layout: 'top',
	theme: 'defaultTheme',
	type: 'alert',
    text: '', // can be html or string
    dismissQueue: true, // If you want to use queue feature set this true
    template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
    animation: {
    	open: {height: 'toggle'},
    	close: {height: 'toggle'},
    	easing: 'swing',
        speed: 500 // opening & closing animation speed
    },
    timeout: notify_time, // delay for closing event. Set false for sticky notifications
    force: true, // adds notification to the beginning of queue when set to true
    modal: false,
    maxVisible: 5, // you can set max visible notification for dismissQueue true option,
    killer: false, // for close all notifications before show
    closeWith: ['click'], // ['click', 'button', 'hover', 'backdrop'] // backdrop click will close all open notifications
    callback: {
    	onShow: function() {},
    	afterShow: function() {},
    	onClose: function() {},
    	afterClose: function() {}
    },
    buttons: false
};

function parse_bad_json(badjson){
	error_message_start = badjson.indexOf('{"');
	error_message_end = badjson.indexOf('"@BADJSON@"}');
	//console.log(error_message_start);
	//console.log(error_message_end);
	if(error_message_start != -1 && error_message_end != -1){
		console.log("I PARSED BAD JSON!");
		json = badjson.substr(error_message_start,error_message_end);
		console.log(json);
		json = $.parseJSON(json);
		return json;
	}
	else
	{
		return false;
	}
}

//AJAX Notification
function ajax_notify(data,position){
	try{
		//console.log("Parsing JSON");
		json = $.parseJSON(data);
		text = json.text;
		type = json.type;
		//console.log(json);
	} catch(exception){
		//console.log(data);
		json = parse_bad_json(data);
		if(json !== false){
			text = json.text;
			type = json.type;
		}
		else{
			data = data.replace(/'/g, "&#39;");
			text = "<h4>ADMIN MODE</h4><input autofocus class='form-control' type='text' value='"+data+"'>";
			type = "information";
		}
		
		notify_time = false;
		notify_closeWith = 'button';
	}

	last_alert = notification(text,type,position);
	
	if (typeof json === 'undefined') {
		json = false;
	}
	return json;
}

function notification(text,type,position){
	var n = noty({
		layout: position,
		text: text.trim(),
		type: type,
		timeout: notify_time,
		closeWith: [notify_closeWith]
	});
	return n;
}

function quicknoty(text,position){
	var n = noty({
		layout: "center",
		text: text.trim(),
		type: "success",
		timeout:200,
		killer: true
	});
  
}