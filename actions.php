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

			case "action":
			$action = $_["action"];
			$arg1 = $_["arg1"];
			$object_name = $_["object"];
			$id = $_["id"];

			$object = new Entity($object_name."_actions",Variable::actions_fields());
			$object = $object->getById($id);

			if($arg1 == 2){
				$gpio = $object["gpio"]; 
				include(CORE_DATAS."gpio/pinstate.data");
				$arg1 = $data ? 0 : 1;
			}

			$command = file_get_contents(USER_OBJECTS.$object_name."/actions/".$action."/"."action.txt");
			$command .= " ".$object["gpio"]." ".$arg1;
			exec($command,$result,$out);
			if($out != 0){
				Draw::ajax_notify(t("This doesn't work, please check your configuration"),"error");
			}
		}
	}
}
?>