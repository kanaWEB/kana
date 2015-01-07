var push_notification_speed = 3000;
var push_notifications_state = true;
var desktop_notification_state = false;
var Notification = window.Notification || window.mozNotification || window.webkitNotification;


function isObject(val) {
    if (val === null) { return false;}
    return ( (typeof val === 'function') || (typeof val === 'object') );
}

if(push_notifications_state == true){
waitfor_notifications();

Notification.requestPermission(function (permission) {
		if(permission == "granted"){
			desktop_notification_state = true;
		}
		//console.log(permission);
	});
}

function waitfor_notifications(){
	console.log("AJAX TIMER : Push notifications (core/views/footer/push.js)");
	pushnotification_timer = setInterval(function(){
		check_notifications();
	},push_notification_speed);
}

function check_notifications(){
	//console.log("Checking notifications");
	
	request = $.ajax({
			url: "actions.php",
			dataType: "json",
			data: {type: "data", data: "pushnotification"}
		});

	request.done(function ( data ) {
		
			//We check if data is an object so we avoid spamming debug
			isdata_object = isObject(data);
		
			if(isdata_object){
				//console.log("Getting Notification!")
				//Manage HTML5 Notifications if possible
				if(desktop_notification_state == false){
					//console.log("Ajax notify selected");
					data = JSON.stringify(data);
					ajax_notify(data,"bottomRight");
				}
				else{
					desktop_notification(data.text);
				}
			}
		});

	request.fail(function( jqXHR, textStatus ) {
			if(textStatus == "error"){
				clearInterval(pushnotification_timer);
  				console.log(textStatus);
  				if(desktop_notification_state == false){
					//console.log("Ajax notify selected");
					ajax_notify("Connection lost","bottomRight");
				}
				else{
					desktop_notification("Connection lost");
				}
  			}
		});
}

function desktop_notification(text){
	console.log(text)
	text = text.replace(/'/g, "&#039;");
	console.log(text)
	var instance = new Notification(
			"Kana", {
				body: text
			}
		);
	return false;
}