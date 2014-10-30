<?php
class data{

	//We saved the data inside a custom database
	//State is not mandatory
	public static function save_data($data,$type,$time,$state=""){
		$dir = "/etc/kana/data/";

		if (!is_dir(DATA_DIR)) {
			mkdir(DATA_DIR);
		}

//We save the data with a timestamp
		$db_fields = array(
			"data" => "text",
			"timestamp" => "int",
			"state" => "text"
			);

//We generate a table to store data
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
			$data_db = new Entity($type,$db_fields,$table,"");
		}
		$data_db->setData($data);
		$data_db->setState($state);
		$data_db->setTimestamp(time());
		$data_db->save();

	}

	//Check the state of a data (if there is a state)
	public static function check_state($state_to_check,$state){
		if($state_to_check == ""){
			return True;
			echo "No state \n";
		}

		if($state_to_check == $state){
			echo "State corresponding \n";
			return True;
		}
		else
		{
			echo "State not corresponding \n";
			return false;
		}
	}

	//@todo Check schedule
	public static function check_triggers($data,$type,$time,$state="")
	{
		//Check if a code has a trigger
		$db_triggers = new Entity("Triggers");

	//Check one code triggers
		$trigger = $db_triggers->load([
			"code" => $data,
			"trigger" => "one",
			"trigger_object" => $type,
			]);




	//Check all code triggers
		$alltrigger = $db_triggers->load([
			"trigger" => "all",
			"trigger_object" => $type,
			]);

//if there is a trigger
		if($trigger){
	//If there is a trigger linked with the data
			if($data == $trigger["code"]){
		//If there is a scenario linked with the trigger
				$db_scenario = new Entity("Scenario");
				$scenario = $db_scenario->load([
					"id_trigger" => $trigger["id"]
					]);
				if($scenario){
					$trigger_state_check = Data::check_state($trigger["state"],$state);

					//If the data have the state asked (also true if no state)
					if($trigger_state_check){
						//Check timeout time
						//@todo let's users define the timeout
						$timeout_time = $trigger["timestamp"] + $trigger["timeout"];
						echo $type." - ".$data." - ".$state."\n";
						echo "Timeout: ".$time - $timeout_time."\n";

						//If timeout is ok
						if(($timeout_time < $time) || ($time = 0)){
							echo " Launching action \n";
							$db_triggers->change(array('timestamp'=>$time),array('id'=>$trigger["id"])); //Change timeout
							Functions::launch_background("./action.py '".$scenario["action_tag"]."'"); //Launch action
						}
					}
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

			$alltrigger_state_check = Data::check_state($alltrigger["state"],$state);

			if($alltrigger_state_check){

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

}
?>