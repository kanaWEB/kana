<?php
class data{

	public static function save_data($data,$type,$time){
		$dir = "/etc/kana/data/";

		if (!is_dir(DATA_DIR)) {
			mkdir(DATA_DIR);
		}


//We save the data with a timestamp
		$db_fields = array(
			"data" => "text",
			"timestamp" => "int"
			);

//We generate a table to store datas
		$table = $dir.$type.".db";
//echo $_["data"];

		if(!file_exists($table)){
			$data_db = new Entity($type,$db_fields,$table,false);
			$data_db->create();
		}

		$data_db = new Entity($type,$db_fields,$table,false);
		$count_data = $data_db->rowCount();

//We don't want to store too much codes so we reset the table every 10 recorded codes
		if($count_data > 10){
			$data_db->drop();
			$data_db = new Entity($type,$db_fields,$table,false);
		}
		$data_db->setData($data);
		$data_db->setTimestamp(time());
		$data_db->save();

	}

	public static function check_triggers($data,$type,$time){
		//Check if a code has a trigger
		$db_triggers = new Entity("Triggers");

	//Check one code triggers
		$trigger = $db_triggers->load([
			"code" => $data,
			"trigger" => "one",
			"trigger_object" => $type
			]);

	//Check conditional trigger (trigger = cond)
	//@todo conditional trigger

	//Check timed schedule triggers (trigger = schedule)
	//@todo timed period triggers


	//Check all code triggers
		$alltrigger = $db_triggers->load([
			"trigger" => "all",
			"trigger_object" => $type
			]);


	//If there is a trigger linked with the data
	//@todo refactor
		if($data == $trigger["code"]){
		//Get Scenario
			$db_scenario = new Entity("Scenario");
			$scenario = $db_scenario->load([
				"id_trigger" => $trigger["id"]
				]);

		//If there is a scenario linked with the trigger
			if($scenario){
			//Check timeout time
			//@todo let's users define the timeout
				$timeout_time = $trigger["timestamp"] + $trigger["timeout"];
				echo $type." - ".$data."\n";
				echo $timeout_time - $time."\n";

			//If timeout is ok
				if($timeout_time < $time){
			$db_triggers->change(array('timestamp'=>$time),array('id'=>$trigger["id"])); //Change timeout
			Functions::launch_background("./action.py '".$scenario["action_tag"]."'"); //Launch action
		}
	}
}

	//If there is an trigger for all actions
	//@todo refactor
if($alltrigger){
	$db_scenario = new Entity("Scenario");
	$scenario = $db_scenario->load([
		"id_trigger" => $alltrigger["id"]
		]);

		//Check if a scenario is link to the trigger
	if($scenario){
		$timeout_time = $alltrigger["timestamp"] + $alltrigger["timeout"];
		echo $type." - ".$data."\n";
		echo $timeout_time - $time."\n";
		if($timeout_time < $time){
			$db_triggers->change(array('timestamp'=>$time),array('id'=>$alltrigger["id"]));
			Functions::launch_background("./action.py '".$scenario["action_tag"]."'");
		}
	}
}

}

}
?>