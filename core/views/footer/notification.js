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

//AJAX Notification
function ajax_notify(data){
	try{
		json = $.parseJSON(data);
		text = json.text;
		type = json.type;
	} catch(exception){
		console.log(data);
		data = data.replace(/'/g, "&#39;");
		text = "<h4>ADMIN MODE</h4><input autofocus class='form-control' type='text' value='"+data+"'>";
		type = "information";
		notify_time = false;
		notify_closeWith = 'button';
	}

	last_alert = notification(text,type);
	

	//tab_notification.push(last_alert);
	//console.log("ADD ITEM INTO ARRAY");
	//console.log(tab_notification.length);

	//setTimeout(function () {
		//console.log("closing noty");
		//console.log("GET ITEM INTO ARRAY");
		//console.log(tab_notification[tab_notification.length-1]);
		//$.noty.close(tab_notification[tab_notification.length-1].options.id);
		//tab_notification.pop();
		//console.log(tab_notification.length);
	//}, notify_time);
if (typeof json === 'undefined') {
json = false;
}
return json
}

function notification(text,type){
	var n = noty({
		text: text.trim(),
		type: type,
		timeout: notify_time,
		closeWith: [notify_closeWith]
	});
	return n
}