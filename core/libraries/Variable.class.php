<?php
class Variable{

//Make a settingsmenu item for objects (icon/text/link/label)
	public static function settingsmenu_item($menu_name,$menu_description,$object_dir,$object){
		$settingsmenu_item = [
		"text" => $menu_name,
		"description" => $menu_description,
		"icon" => $object_dir."/icon.png",
		"link" => $object,
		"label" => "label-danger",
		];
		return $settingsmenu_item;
	}

	public static function object_html($object_dir){
		$object_name = self::objectName($object_dir);
		$object_name = 'data-content="<img src="plugins/objects/'.$object_dir.'/icon.png">"  '.$object_name;
		$object_html = [
		"text" => $object_name,
		"img" => $object_dir
		];
		return $object_html;
	}

	public static function object_nameicon($entity){
		$dir = Variable::sensors_or_objects_dir($entity);
		$object_name = self::objectName("plugins/".$dir.$entity);
		$object_html = '<h1><img src="plugins/'.$dir.'/'.$entity.'/icon.png"> '.$object_name.'</h1>';
		return $object_html;
	}

	public static function sensors_or_objects_dir($entity){
		if (file_exists(USER_OBJECTS.$entity)){$dir = "objects/";}
		if (file_exists(USER_SENSORS.$entity)){$dir = "sensors/";}
		//if (!isset($dir)){$dir = false;};
		return $dir;
	}

	public static function navtab_item($category,$text,$link,$menu=false){
		if($menu){
			$link_begin = "settings.php?category=".$category."&menu=".$menu."&tab=";
		}
		else{
			$link_begin = "settings.php?category=".$category."&tab=";
		}

		$navtab_item = [
		"text" => $text,
		"link" => $link_begin.$link
		];

		return $navtab_item;
	}

//Searching a data everywhere we can
	public static function data_dir($data_link){
		
		$data_link_array = explode("/",$data_link);

		if(count($data_link_array) != 1){
			$plugin = $data_link_array[0];
			$file = $data_link_array[1];
			if($plugin == "object"){
				return $file;
			}
		}
		else{
			$plugin = $data_link;
			$file = $data_link;
		}

//You can prioritize similar named data here, this can be used to override how a data inside core works.
//@todo data is invariable (unlike donnee in french)
		$data_files[] = USER_DATA.$data_link.".data"; //We see if there are user defined data
		$data_files[] = USER_VIEWS.$plugin."/data/".$file.".data"; //We see if a view has data
		$data_files[] = USER_OBJECTS.$plugin."/data/".$file.".data"; //We see if objects has data
		$data_files[] = CORE_DATA.$data_link.".data"; //Finally we check if core has data

		//If we are in debug mode dump file path array
		if(DEBUG){
			echo "Searching Data files...";
			var_dump($data_files);
		}

		//Search for data files inside directories
		foreach($data_files as $data_file){
			if(file_exists($data_file)){
				return $data_file;
			}
		}
	}

//Get a data from a data file, you can use a modifiers array to add variables to the data
//@todo verify if it is use everywhere
//For now most data works with global variable and generate a lot of variables that is not required by the application
	public static function get_data($data_link,$modifiers=false){
		//We search every data directory
		$data_file = Variable::data_dir($data_link);

		//If a modifier is set we convert it into a variable for the data
		if($modifiers){
			$variable_name = array_keys($modifiers);
			foreach($modifiers as $key => $modifier){
				//var_dump($key);
				//var_dump($modifier);
				$$key = $modifier;
				//var_dump($sensor_type);
			}
		}

		//var_dump($data_file);

		//@todo Security test needed
		//If a file is founded include it
		if(file_exists($data_file)){
			include($data_file);
		}
		else //If a file is not founded 
		{
			if(file_exists(USER_OBJECTS."/".$data_file)){
				$object_name = $data_file;
				include(CORE_DATA."object.data");
			}
		}

		if(isset($data)){
			return $data;
		}
		else{
			return False;
		}
	}


/*

Fields

 */

//Get all actions arguments from json
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

//Get action arguments from json
public function action_args($action){
	$args = json_decode(html_entity_decode($action["args"]));
	foreach($args as $key_arg => $arg){
		$action[$key_arg] = $arg;
	}
	unset($action["args"]);
	return $action;
}

//Markdown to inputs
public static function md2var($file){
	$input_data = file($file);
	$variables = explode("|",$input_data[0]);
	$variables = array_map('trim',$variables);
	$values = explode("|",$input_data[2]);
	$values = array_map('trim',$values);
	$input = array_combine($variables,$values);
	return $input;
}

//Markdown to array
public function md2vars($file){
	$input_data = file($file);
	foreach($input_data as $key => $data){
		$variables = explode("|",$input_data[$key]);
		$variables = array_map('trim',$variables);
		if(count($variables) > 1 ){
			if($variables[1] == "repo"){
				//@todo Refactor markdown reader for error handling
				$values = explode("|",$input_data[$key+2]);
				$values = array_map('trim',$values);
				array_shift($variables);
				array_pop($variables);
				array_shift($values);
				array_pop($values);
				$inputs[] = array_combine($variables,$values);
			}
		}
	}
	//var_dump($inputs);
	return $inputs;
}

//Availables Actions menu item (Triggers)
public function md2menuitem($category,$object_name,$available_md_item){

	if($category == "scenario"){
		$link =  $_SERVER['SCRIPT_NAME']."?category=".$category."&menu=triggers&tab=".$object_name."&trigger=".$available_md_item;
		$dir = "triggers";
	}
	else{
		$link =  $_SERVER['SCRIPT_NAME']."?category=".$category."&menu=".$object_name."&tab=action&action=".$available_md_item;
		$dir = "actions";
	}

	$md_dir = USER_OBJECTS.$object_name."/".$dir."/".$available_md_item."/info/";


	//Get name of actions/triggers (internationalized)
	$mdfile_name = $md_dir."text.md";
	$mdfile_name_translated = $md_dir."text.".$_SESSION["LANGUAGE"].".md";

	//Get Description file (internationalized)
	$mdfile_description = $md_dir."description.md";
	$mdfile_description_translated = $md_dir."description.".$_SESSION["LANGUAGE"].".md";

	if(file_exists($mdfile_name_translated)){
		$text = file_get_contents($mdfile_name_translated);
		$description = file_get_contents($mdfile_description_translated);
	}
	else{
		if(file_exists($mdfile_name)){
			$text = file_get_contents($mdfile_name);
		}
		else{
			$text = $mdfile_name;
		}
		if(file_exists($mdfile_description)){
			$description = file_get_contents($mdfile_description);
		}
		else{
			$description = $mdfile_description;
		}
	}

	$menu_item = [
	"text" => $text,
	"description" => $description,
	"link" =>  $link,
	"dir" => $available_md_item
	];

	return $menu_item;
}

//Which menu has an object 
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

	if(file_exists($path."/electronics")){
		$menu_name["electronics"] = True;
	}

	if(file_exists($path."/codes")){
		$menu_name["codes"] = True;
	}

	if(!isset($menu_name)){
		$menu_name = false;
	}

	return $menu_name;
}

//Markdown to Name of an objects
public function objectName($object_dir){
	//var_dump("objectName Path:".$object_dir);
	//Check if objects directory exists
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

public function objectDescription($object_dir){
	//Check if objects directory exists
	if(is_dir($object_dir)){
		//Check for menu translation
		$translated_menu_dir = $object_dir."/info/description.".$_SESSION["LANGUAGE"].".md";

		if(file_exists($translated_menu_dir)){
			$menu_filepath = $translated_menu_dir;
		}
		else{
		//If no translation check for menu 
			if(file_exists($object_dir."/info/description.md")){
				$menu_filepath = $object_dir."/info/description.md";
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


//Markdown to Name of an objects
public function objecttags($object_dir){
	//Check if objects directory exists
	if(is_dir($object_dir)){
		//Check for menu translation
		$translated_menu_dir = $object_dir."/info/tags.".$_SESSION["LANGUAGE"].".md";

		if(file_exists($translated_menu_dir)){
			$menu_filepath = $translated_menu_dir;
		}
		else{
		//If no translation check for menu 
			if(file_exists($object_dir."/info/tags.md")){
				$menu_filepath = $object_dir."/info/tags.md";
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

public function objects_to_webobjects($current_group){
	$objects = Functions::getdir(USER_OBJECTS);
	
	$key = 0;
		//For each objects
	foreach($objects as $object){

		//Get actions from objects	
		$actions_db = new Entity("actions",$object);
		$actions_list = $actions_db->loadAll([
			"group_key" => $current_group
			]);

		//If there are actions
		if($actions_list){
			foreach($actions_list as $action){

				$webobjects[$key]["id"] = $action["id"];
				$webobjects[$key]["widget"] = $action["action"];
				$webobjects[$key]["type"] = $object;

				$webobjects[$key]["name"] = htmlspecialchars_decode($action["entity_name"]);
				$webobjects[$key]["description"] = htmlspecialchars_decode($action["entity_description"]);
				$webobjects[$key]["icon"] = USER_OBJECTS.$object."/icon.png";

				if(file_exists(USER_OBJECTS.$object."/gpios")){
					$object_db = new Entity("config",$object);
					$object_db = $object_db->getById($action["object_key"]);
				}
				else{
					$object_db = false;
				}

				$md_actions_dir = (USER_OBJECTS.$object."/actions/".$action["action"]."/actions/");
				$actions_array = Functions::getdir($md_actions_dir);

				foreach($actions_array as $actions){
					$webobjects[$key]["actions"][] = Variable::md2var($md_actions_dir."/".$actions);
				}

					//Get GPIO state (if objects have 1,n GPIO)
				if(isset($object_db["gpio"])){
					$gpio = $object_db["gpio"];
					include(CORE_DATA."gpio/pinstate.data");
					$webobjects[$key]["state"] = $data ? "success" : "danger";
				}
				else{
					//Else get state from database
					$webobjects[$key]["state"] = $action["state"] ? "success" : "danger";
				}
				//}
				$key++;
			}
		}
	}
	//var_dump($webobjects);
	return $webobjects;
}

public function sensors_to_websensors($current_group){
	$sensors_type = Functions::getdir(USER_SENSORS);
	$key = 0;
	foreach($sensors_type as $sensor_type){
		$sensors_objects_db = new Entity("config",$sensor_type);
		$sensors_list = $sensors_objects_db->loadAll([
			"group_key" => $current_group
			]);
		//var_dump($sensors_list);

		if($sensors_list){
			$sensors_db = new Entity("core","Sensors"); 
			foreach($sensors_list as $sensor_object){
				$websensors[$key] = $sensors_db->load([
					"sensor_id" => $sensor_object["sensor_id"]
					]);
				if($websensors[$key]){
					$websensors[$key]["name"] = $sensor_object["entity_name"];
					$websensors[$key]["description"] = $sensor_object["entity_description"];
					$websensors[$key]["icon"] = USER_SENSORS.$websensors[$key]["sensor_type"]."/icon.png";
					if($websensors[$key]["sensor_battery"] != "ON"){
						$battery_level = intval($websensors[$key]["sensor_battery"]);
						if($battery_level < 30){
							$websensors[$key]["sensor_battery_class"] = "progress-bar-danger";
						}

						if($battery_level > 30 && $battery_level < 60){
							$websensors[$key]["sensor_battery_class"] = "progress-bar-warning";
						}

						if($battery_level > 60){

							$websensors[$key]["sensor_battery_class"] = "progress-bar-success";
						}
					//var_dump($battery_level);
					}

					$key++;
				}
			}
		}
	}
	if(isset($websensors)){
		if(!$websensors[0]){
			return false;
		}
		else{
			return $websensors;
		}
	}
	else
	{
		return false;
	}
}

}
?>