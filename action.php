<?php
//This file is used to emulate the functionalities of yana server
//For more information on yana server / yana client
//Please go to http://blog.idleman.fr/ (fr)

//We include basic functionalities
include("core/common.inc");

//If action is set
if(isset($_["action"])){

	//The only action this file has to handle is getting the list of speech commands
	//action.php?action=GET_SPEECH_COMMAND
	if($_["action"] == "GET_SPEECH_COMMAND"){
	//If a token is set
		if(isset($_["token"])){

		//Check if the token exists and is associated with an user
			$currentUser = new User;
			$currentUser->check_token($_["token"]);

		//If token is associated to no user, log it
			if(!$currentUser->id()){
				$tokenlog_db = new Entity("core","TokenLog");
				$ip_selected = $tokenlog_db->load([
					"ipaddress" => $_SERVER['REMOTE_ADDR'],
					"token" => $_["token"]
					]);

			//If there was already a request from this ip increment it
				if($ip_selected){
					$nbrequest = $ip_selected["nbrequest"] + 1;
					$tokenlog_db->change([
						"nbrequest" => $nbrequest,
						"timestamp" => time()
						], 
						['id'=>$ip_selected["id"]]
						);

				}
			//If this is the first request then save it
				else{
					$tokenlog_db->SetIpaddress($_SERVER['REMOTE_ADDR']);
					$tokenlog_db->SetTimeStamp(time());
					$tokenlog_db->SetToken($_["token"]);
					$tokenlog_db->SetNbrequest(1);
					$tokenlog_db->Save();
				}
			echo '{"error":"invalid or missing token"}'; //Ouput permission error message
		}
		//If token is associated with an user display list of commands
		else
		{
			include(CORE_DATA."url/commandsyana.data");
			echo json_encode($data);
		}
	}
	//If no token was specified
	else
	{
		echo '{"error":"no token"}';
	}
}
}
	//if($_["action"] == "GET_SPEECH_COMMAND"){
/*		$json_actions['commands'][] = [
		"command" => "yana",
		"url" => "etc",
		'confidence' => '0.9'
		];

		echo json_encode($json_actions);
	//}
	*/

		?>