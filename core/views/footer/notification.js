//AJAX Notification (in yellow)
function ajax_notify(data){
	//console.log(data);
	text = data.split("!::!");

	if (text.length == 1){
		last_alert = notification(text[0],"error")
	}

	else
	{
		switch(text[0].trim()){
			case "success":
			last_alert = notification(text[1],"success")
			break;

			case "warning":
			last_alert = notification(text[1],"warning")
			break;

			case "error":
			last_alert = notification(text[1],"error")
			break;

			case "info":
			last_alert = notification(text[1],"information")
			
			default:
			last_alert = notification(text[0],"alert")
			break;
		}
		
	}

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

}

function notification(text,type){
	var n = noty({
		text: text.trim(),
		type: type,
	});
	return n
}