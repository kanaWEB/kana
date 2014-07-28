<?php
class Variable{


//Searching a data everywhere we can
	public static function data_dir($data_link){
		$data_link_array = explode("/",$data_link);

		if(count($data_link_array) != 1){
		$plugin = $data_link_array[0];
		$file = $data_link_array[1];
		}
		else{
			$plugin = $data_link;
			$file = $data_link;
		}

//You can prioritize similar named datas here, this can be used to override how a data inside core works.
		$data_files[] = USER_DATAS.$data_link.".data";
		$data_files[] = USER_VIEWS.$plugin."/datas/".$file.".data";
		$data_files[] = USER_OBJECTS.$plugin."/datas/".$file.".data";
		$data_files[] = CORE_DATAS.$data_link.".data";

	//var_dump($data_files);

		foreach($data_files as $data_file){
			if(file_exists($data_file)){
				return $data_file;
			}
		}
		var_dump($data_link);
		if($data_link == $plugin."list"){
			echo "TEST";
		}

	}

	public function actions_fields(){
		$db_fields = [
		"action" => "text",
		"args" => "text",
		"object_key" => "int",
		"group_key" => "int"
		];
		return $db_fields;
	}

	public function actions_args($actions){
		foreach($actions as $key_action => $action){
			$args = json_decode(html_entity_decode($action["args"]));
			foreach($args as $key_arg => $arg){
				$actions[$key_action][$key_arg] = $arg;
			}
			unset($actions[$key_action]["args"]);
		}
		return $actions;
	}

	public function action_args($action){
		$args = json_decode(html_entity_decode($action["args"]));
			foreach($args as $key_arg => $arg){
				$action[$key_arg] = $arg;
			}
			unset($action["args"]);
		return $action;
	}

	public function md2var($file){
		$input_data = file($file);
		$variables = explode("|",$input_data[0]);
		$variables = array_map('trim',$variables);
		$values = explode("|",$input_data[2]);
		$values = array_map('trim',$values);
		$input = array_combine($variables,$values);
		return $input;
	}

	public function object_menus_name($object_name){
		$path = USER_OBJECTS.$object_name;
		if(file_exists($path."/actions")){
			$menu_name["actions"] = True;
		}
		if(file_exists($path."/help")){
			$menu_name["help"] = True;

		}
		if(file_exists($path."/gpios")){
			$menu_name["gpios"] = True;

		}
		return $menu_name;
	}

	public function objectName($object_dir){
	//Check if projects directory exists
		if(is_dir($object_dir)){
		//Check for menu translation
			$translated_menu_dir = $object_dir."/info/name.".$_SESSION["LANGUAGE"].".md";

			if(file_exists($translated_menu_dir)){
				$menu_filepath = $translated_menu_dir;
			}
			else{
		//If no translation check for menu 
				if(file_exists($object_dir."/info/name.md")){
					$menu_filepath = $object_dir."/info/name.md";
				}
			}
		}

		if(isset($menu_filepath)){
			$menu_name = file_get_contents($menu_filepath);
			return $menu_name;
		}
		else
		{
			return false;
		}
	}
}
?>