<?php
include("core/common.inc"); //Common libraries

// If user is an admin
if($currentUser->isadmin()){

//Show selected menu or default menu
	if(isset($_["menu"])){
		
		//Objects Redirector (menu is objects and name is set)
		if( ($_["menu"] == "objects") && isset($_["name"])){
			$object_name = $_["name"];
			$leftmenu_active = "objects&name=".$object_name;
			$filename = "objects";
			//If no tab is set then redirect
			if(!isset($_["tab"])){
				include(CORE_FORMS."settings/objects/objects.redirect");
			}
		}
	//General item
		else
		{
			$leftmenu_active = $_["menu"];
			$filename = $_["menu"];
		}
	}
//Menu is not set
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
//if user is not admin
else
{
	include(CORE_VIEWS."header/min_header.view");
	$tpl->draw(CORE_VIEWS."modal/permissions_denied");
}
?>