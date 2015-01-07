<?php
class data{

	//We saved the data inside a custom database
	//State is not mandatory
	public static function save_data($data_id,$data,$type,$time,$state="",$maxdata=false){
		//$dir = "/etc/kana/data/";

		if (!is_dir(DATA_DIR)) {
			mkdir(DATA_DIR);
		}

		$dir = DATA_DIR;
		//echo $dir;
		//We save the data with a timestamp
		

		//We generate a table to store data
		$table = $dir.$type.".db";
		//echo $_["data"];

		if(!file_exists($table)){
			$data_db = new Entity("data",$type);
			$data_db->create();
		}

		$data_db = new Entity("data",$type);
		$count_data = $data_db->rowCount();

		//We don't want to store too much codes so we reset the table every 10 recorded codes
		if($maxdata){
			if($count_data > $maxdata){
				$data_db->drop();
				$data_db = new Entity("data",$type);
			}
		}

		$data_db->setData($data);
		$data_db->setState($state);
		$data_db->setData_id($data_id);
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
		else{
			echo "State not corresponding \n";
			return false;
		}
	}

	public static function check_sensor($sensor_data,$time){
		$db_sensors = new Entity("core","Sensors");
		$sensor_info = $db_sensors->load([
			"sensor_id" => $sensor_data["id"]
			]);

		var_dump($sensor_info);

		if(!$sensor_info){
			$db_sensors->setsensor_id($sensor_data["id"]);
			$db_sensors->setsensor_type("unknown");
			$db_sensors->setsensor_save(0);
			$db_sensors->setsensor_name($sensor_data["id"]);
			$db_sensors->setsensor_battery($sensor_data["battery"]);
			$db_sensors->setsensor_lastvalue($sensor_data["value"]);
			$db_sensors->save();
			echo "New sensor founded! save it";
			return false;
		}
		else{
			$db_sensors->change(["sensor_lastvalue" => $sensor_data["value"]],['sensor_id'=>$sensor_data["id"]]);
			$db_sensors->change(["sensor_battery" => $sensor_data["battery"]],['sensor_id'=>$sensor_data["id"]]);
			return $sensor_info;
		}
	//	}
		/*
		if(isset($sensor_data["value"])){
				echo "Triggers OFF!!!!! check Data.class.php L79\n";
				echo "Sensor triggered! \n ";
				//self::save_data($sensor_data["id"],$sensor_data["value"],"sensors",$time,$sensor_data["battery"],30);
				//self::check_triggers($sensor_data["id"],"sensors",$time);
		}
	*/
		
	}

	//@todo Check schedule
	public static function check_triggers($data,$type,$time,$state=""){

		//var_dump($type);
		//var_dump($data);

		//Check if a code has a trigger
		$db_triggers = new Entity("core","Triggers");

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

		echo "Checking triggers \n ";
		//if there is a trigger

		if($trigger){
			
			//If there is a trigger linked with the data
			if($data == $trigger["code"]){
				//If there is a scenario linked with the trigger
				$db_scenario = new Entity("core","Scenario");
				$scenario = $db_scenario->load([
					"id_trigger" => $trigger["id"]
				]);
				
				if($scenario){

					echo "Scenario founded for Trigger ONE \n";
					$trigger_state_check = Data::check_state($trigger["state"],$state);

					//If the data have the state asked (also true if no state)
					if($trigger_state_check){
						
						//Check timeout time
						//@todo let's users define the timeout
						$timeout_time = $trigger["timestamp"] + $trigger["timeout"];
						echo $type." - ".$data." - ".$state." \n ";
						echo "Timeout: ".$time - $timeout_time." \n ";
						echo $scenario["action_tag"]." \n ";
						
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
			$db_scenario = new Entity("core","Scenario");
			$scenario = $db_scenario->load([
				"id_trigger" => $alltrigger["id"]
				]);

			$alltrigger_state_check = Data::check_state($alltrigger["state"],$state);

			if($alltrigger_state_check){

				//Check if a scenario is link to the trigger
				if($scenario){
					echo "\nScenario found for trigger ALL! \n";
					$timeout_time = $alltrigger["timestamp"] + $alltrigger["timeout"];
					echo $type." - ".$data." - ".$state." - ";
					echo "Timeout: ".($time - $timeout_time)." \n";
					echo $scenario["action_tag"]." \n";
					if($timeout_time < $time){
						echo "Time out OK!";
						$db_triggers->change(array('timestamp'=>$time),array('id'=>$alltrigger["id"]));
						Functions::launch_background("./action.py '".$scenario["action_tag"]."'");
				}
			}
		}
	}

}

	public static function db_available($type){
		if(file_exists(USER_SENSORS.$type."/db/")){
			return Functions::getdir(USER_SENSORS.$type."/db/");
		}
		else{
			return false;
		}
	}

}
?>