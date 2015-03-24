//check for notification and ajax request
var command = "";
var rootLabel_default = $("#rootLabel").html();

function rootAction(button){
	$(button).attr("disabled",true);
	$('#loading-indicator').show();
	sshUser = $("#sshUser").val();
	sshPassword = $("#sshPassword").val();
	$.ajax({
		type:"POST",
		url: "actions.php",
		data: {type: "rootcommand", sshUser: sshUser , sshPassword: sshPassword, command: command}
	}).done(function ( data ) {
		$(button).attr("disabled",false);
		$('#loading-indicator').hide();
		ajax_notify(data,"top");
	});
}

$(".rootcommand").click(function(){
command = $(this).attr("id");
rootLabel = rootLabel_default + " :" + command + ".sh";
$("#rootLabel").html(rootLabel);
});