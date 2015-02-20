<?php
include("core/common.inc"); //Common libraries
// If user is an admin
if($currentUser->isadmin()){

	//Category (First level menu)
	if(isset($_["category"])){
		$category_selected = $_["category"];
	}
	else{
		$category_selected = DEFAULT_CATEGORY_SETTINGS;
	}
	//Menu (Second level menu)
	if(isset($_["menu"])){
		$menu_selected = $_["menu"];
	}
	else{
		//Default menu for each tab
		switch($category_selected){
			case 'configuration':
				$menu_selected = "pref";
			break;

			case "scenario":
				$menu_selected = "triggers";
			break;

			case "objects":
				$menu_selected = "allobjects";
			break;

			case "sensors":
				$menu_selected = "allsensors";
			break;
		}
	}

	//Tab (Third level menu)
	if(isset($_["tab"])){
		$tab_selected = $_["tab"];
	}
	else
	{
		$tab_selected = false;
	}

	if(isset($_["action"])){
		$action_selected = $_["action"];
	}
	else
	{
		$action_selected = false;
	}

	if(isset($_["submit"])){
		include(CORE_FORMS."settings.post");
	}
	else{

		include(CORE_FORMS."settings.form");
	}
}
//if user is not admin
else{
	include(CORE_VIEWS."header/min_header.view");
	$tpl->draw(CORE_VIEWS."modal/permissions_denied");
}
?>