<?php
include("core/common.inc");

if($currentUser->isuser()){
	//@todo check user right to do actions
	if(isset($_["type"])){
		$type = $_["type"];
		switch($type){

			//Include
			case "data":
			$data_file = Variable::data_dir($_["data"]);
			include($data_file);
			break;

			case "gpio":
			if($currentUser->isadmin()){
				$command = "/usr/local/bin/gpio write ".$_["gpio"]." ".$_["state"];
				exec($command,$result,$out);
				echo $out;
			}
			
			break;

			case "action":
			$action_name = $_["action"];
			$object_name = $_["object"];
			$action_nb = $_["action_nb"];
			$id = $_["id"];

			$action_db = new Entity($object_name."_actions",Variable::actions_fields());
			$action = $action_db->getById($id);
			$object_db = new Entity($object_name);
			$object = $object_db->getById($action["object_key"]);

			//Get command number
			$command_file = file(USER_OBJECTS.$object_name."/actions/".$action_name."/"."action.txt");
			
			//COMMAND TREATMENT
			$command_array = trim($command_file[$action_nb]);
			$command_array = explode("[",$command_array);
			
			
			//Program (without arguments)
			$commands[0] = $command_array[0];
			//If command is inside object plugin
			$user_command = USER_OBJECTS.$object_name."/actions/".$action_name."/".$commands[0];
			if(file_exists($user_command));
			{
				$command[0] = dirname($_SERVER['SCRIPT_FILENAME'])."/".$user_command;
			}
			

			//Arguments
			array_shift($command_array); //Remove command
			foreach($command_array as $key => $argument){
				
				switch(trim($argument)){
					case "gpio]":
					$commands[$key+1] = " ".$object["gpio"]." "; 
					break;

					case "state]":
					if(isset($_["state"])){
					$state = trim($_["state"]);
					}
					else
					{
						$state = false;
					}

					switch($state){
						case "on":
						$state = 1;
						break;

						case "off":
						$state = 0;
						break;

						case "change_gpio":
						$gpio = $object["gpio"];
						include(CORE_DATAS."gpio/pinstate.data");
						$state = $data ? 0 : 1;

						break;

						default:
						$state = false;
						break;
					}	
					$commands[$key+1] = $state;
					break;

					default:
					$argument = substr(trim($argument), 0, -1);
					echo $argument;
					$commands[$key+1] = $action[$argument]; 
					break;
				}
			}
		
			$final_command = "";
			foreach($commands as $command){
				$final_command .= $command;
			}

		
			exec($final_command,$result,$out);

			if($out != 0){
				switch ($out){
					case 127:
					$error = t("File not founded");
					break;

					case 1:
					$error = t("System permissions not granted");
					break;

					default:
					$error = t("Unknown Error: ").$out;
					break;
				}

				if(!DEBUG){
				Draw::ajax_notify(t("This doesn't work, please check your configuration: ").$error,"error");
				}
				else
				{
				Draw::ajax_notify("<h1>DEBUG</h1>".$final_command." <br> EXIT CODE: ".$out,"warning");
				}
			}
		}
	}
}
?>