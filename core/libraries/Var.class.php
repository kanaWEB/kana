<?php
class Variable{

	public static function menu_objects($object_name,$tpl,$tab){
/*
NavTab / Name
*/
$link_begin = "settings.php?menu=objects&name=".$object_name."&tab=";

$navtab_items[] = [
"text" => "Add/Remove",
"link" => $link_begin."add-remove"
];

$navtab_items[] = [
"text" => "Actions",
"link" => $link_begin."actions"
];

$navtab_items[] = [
"text" => "Help",
"link" => $link_begin."help"
];

if($tab){ 
	$navtab_item_selected = $link_begin.$tab;
}
else{
	$navtab_item_selected = $link_begin."actions";
}

$tpl->assign("navtab_item_selected",$navtab_item_selected);
$tpl->assign("navtab_items",$navtab_items);
$tpl->draw(CORE_VIEWS."menu/navtab");

return $tpl;
}


public static function data_dir($data_link){
	$data_link_array = explode("/",$data_link);
	$plugin = $data_link_array[0];
	$file = $data_link_array[1];

	$data_files[] = USER_DATAS.$data_link.".data";
	$data_files[] = USER_VIEWS.$plugin."/datas/".$file.".data";
	$data_files[] = USER_OBJECTS.$plugin."/datas/".$file.".data";

	foreach($data_files as $data_file){
		if(file_exists($data_file)){
			return $data_file;
		}
	}
}

public function actions_fields(){
		$db_fields = [
		"state" => "int",
		"action" => "text",
		"command" => "text",
		"object_key" => "int",
		"gpio" => "int",
		"group_key" => "int"
		];
		return $db_fields;
}
}
?>