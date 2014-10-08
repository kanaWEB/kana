tab_notification = [];
notify_time = 5000;

//AJAX Notification
function ajax_notify(data){
	try{
		json = $.parseJSON(data);
		text = json.text
		type = json.type
	} catch(exception){
		text = data
		type = "information"
	}

	last_alert = notification(text,type);
	

	tab_notification.push(last_alert);
	//console.log("ADD ITEM INTO ARRAY");
	//console.log(tab_notification.length);

	setTimeout(function () {
		//console.log("closing noty");
		//console.log("GET ITEM INTO ARRAY");
		//console.log(tab_notification[tab_notification.length-1]);
		$.noty.close(tab_notification[tab_notification.length-1].options.id);
		tab_notification.pop();
		//console.log(tab_notification.length);
	}, notify_time);
if (typeof json === 'undefined') {
json = false;
}
return json
}

function notification(text,type){
	var n = noty({
		text: text.trim(),
		type: type,
	});
	return n
}