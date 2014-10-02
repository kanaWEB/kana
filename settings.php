<?php
include("core/common.inc"); //Common libraries

// If user is an admin
if($currentUser->isadmin()){

//Show selected menu or default menu
	if(isset($_["menu"])){
		
		//Objects item
		if( ($_["menu"] == "objects") && isset($_["name"])){
			$leftmenu_active = "objects&name=".$_["name"];
			$filename = "objects";
			
			/*
			Auto Redirector for objects
			@todo Refactor!
			*/
			
			if(!isset($_["tab"])){
				$object_name = $_["name"];
				if(file_exists(USER_OBJECTS.$object_name."/".$object_name.".txt")){

					
					$object_db = new Entity($object_name);
					$nb_object = $object_db->rowCount();
			//If Electronics was not setup
					if($nb_object == 0){
						$menu_name = Variable::object_menus_name($object_name);

				if(isset($menu_name["gpios"])){ //Gpios needs to be configured?
					redirect("settings","?menu=objects&name=".$object_name."&tab=gpios");
				}
				elseif(isset($menu_name["electronics"])){ //Definite numbers of Pins need to be configured
					redirect("settings","?menu=objects&name=".$object_name."&tab=electronics");
				}
				else
				{
					//No Electronics
					$actions_list = Functions::getdir(USER_OBJECTS."/".$object_name."/actions");
					$nb_actions = count($actions_list);
					if(!isset($menu_name["groups"])){
						if($nb_actions == 1){
							redirect("settings","?menu=objects&name=".$object_name."&tab=action&action=".$actions_list[0]);
						}
						else{
							redirect("settings","?menu=objects&name=".$object_name."&tab=action");
						}	
					}
					else{
						redirect("settings","?menu=objects&name=".$object_name."&tab=groups");
					}		
				}
			}
			else
			{
					//No Electronics
				$actions_list = Functions::getdir(USER_OBJECTS."/".$object_name."/actions");
				$nb_actions = count($actions_list);
				if($nb_actions == 1){
					redirect("settings","?menu=objects&name=".$object_name."&tab=action&action=".$actions_list[0]);
				}
				else{
					redirect("settings","?menu=objects&name=".$object_name."&tab=action");
				}	
			}
		}
		else
		{	//No objects only actions
			redirect("settings","?menu=objects&name=".$object_name."&tab=action");
		}
	}




}
		//General item
else
{
	$leftmenu_active = $_["menu"];
	$filename = $_["menu"];
}
}
else
{
		//Default active menu
	$leftmenu_active = DEFAULT_MENU_SETTINGS;
	$filename = $leftmenu_active;
}

//Settings post
if(isset($_["submit"])){
	$file = CORE_FORMS."/settings/".$filename.".post";
	if(file_exists($file)){
		include(CORE_FORMS."/settings/".$filename.".post");
	}
}
//Settings form
else
{	
	include(CORE_FORMS."/settings.form");
}
}
?>