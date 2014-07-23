<?php
include("core/common.inc");

if($currentUser->isuser()){
	//@todo check user right to do actions
	if(isset($_["type"])){
		$type = $_["type"];
		switch($type){
			case "data":
			$data_link = $_["data"];
			$data_file = Functions::getdata_dir($data_link);
			include($data_file);
			break;

			case "action":
			$action = $_["action"];
			$arg1 = $_["arg1"];
			$object_name = $_["object"];
			$id = $_["id"];

			$object = new Entity($object_name."_actions",Entity::Actions_fields());
			$object = $object->getById($id);

			if($arg1 == 2){
				$gpio = $object["gpio"]; 
				include(USER_DATAS."gpio/pinstate.data");
				$arg1 = $data ? 0 : 1;
			}

			$command = file_get_contents(USER_OBJECTS.$object_name."/actions/".$action."/"."action.txt");
			$command .= " ".$object["gpio"]." ".$arg1;
			exec($command,$result,$out);
			if($out != 0){
				Functions::ajax_notify(t("This doesn't work, please check your configuration"),"error");
			}
		}
	}
}
?>