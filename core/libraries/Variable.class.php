<?php
class Variable{

//Make a leftmenu item for objects (icon/text/link/label)
	public static function leftmenu_item($menu_name,$object_dir,$object){
		$leftmenu_item = [
		"text" => $menu_name,
		"icon" => $object_dir."/icon.png",
		"link" => "objects&name=".$object,
		"label" => "label-danger",
		];
		return $leftmenu_item;
	}

	public static function navtab_item($menu,$text,$link,$name=false){
	if($name){
		$link_begin = "settings.php?menu=".$menu."&name=".$name."&tab=";
	}
	else
	{
			$link_begin = "settings.php?menu=".$menu."&tab=";
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
		//var_dump($data_link);
		if($data_link == $plugin."list"){
			echo "TEST";
		}

	}

/*

Fields

 */

//Actions fields
	public function actions_fields(){
		$db_fields = [
		"action" => "text",
		"args" => "text",
		"object_key" => "int",
		"group_key" => "int"
		];
		return $db_fields;
	}

//Scenario Triggers Fields
	public function scenario_triggers_fields(){
		$db_fields = [
		"trigger_object"=>"text",
		"trigger"=>"text",
		"args"=>"text",
		"trigger_type"=>"text",
		"scenario_group"=>"int",
		];
		return $db_fields;
	}

//Scenario Actions Fields
	public function scenario_actions_fields(){
		$db_fields = [
		"action" => "text",
		"args" => "text",
		"scenario_actions_group_key" => "int"
		];
		return $db_fields;
	}

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
	public function md2var($file){
		$input_data = file($file);
		$variables = explode("|",$input_data[0]);
		$variables = array_map('trim',$variables);
		$values = explode("|",$input_data[2]);
		$values = array_map('trim',$values);
		$input = array_combine($variables,$values);
		return $input;
	}

//Availables Actions menu item
	public function md2menuitem($menu,$tab,$object_name,$available_md_item){
		$dir_tab = $tab."s";
		$md_dir = USER_OBJECTS.$object_name."/".$dir_tab."/".$available_md_item."/md/";


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
			else
			{
			$text = $mdfile_name;
			}
			if(file_exists($mdfile_description)){
			$description = file_get_contents($mdfile_description);
			}
			else
			{
			$description = $mdfile_description;
			}
		}

		$menu_item = [
		"text" => $text,
		"description" => $description,
		"link" =>  $_SERVER['SCRIPT_NAME']."?menu=".$menu."&name=".$object_name."&tab=".$tab."&".$tab."=".$available_md_item,
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
	return $menu_name;
}

//Markdown to Name of an objects
public function objectName($object_dir){
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
}
?>