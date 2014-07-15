<?php
include("core/common.inc"); //Common libraries

// If user is an admin
if($currentUser->isadmin()){

//Show selected menu or default menu
	if(isset($_["menu"])){
		
		if( ($_["menu"] == "projects") && isset($_["name"])){
			$leftmenu_active = $_["name"];
			$filename = "projects";
		}
		else
		{
			$leftmenu_active = $_["menu"];
			$filename = $_["menu"];
		}
	}
	else
	{
		$leftmenu_active = DEFAULT_MENU_SETTINGS;
		$filename = $leftmenu_active;
	}



//Settings post
	if(isset($_["submit"])){
		$file = "core/forms/settings/".$filename.".post";
		if(file_exists($file)){
		include("core/forms/settings/".$filename.".post");
		}
	}
//Settings form
	else
	{	
		//Header
		include(CORE_VIEWS."header/header.view");
		include(CORE_VIEWS."menu/top.view");
		include(CORE_VIEWS."menu/left/left.view");

		//Form
		include(CORE_VIEWS."forms/Form.class.php");
		
		//Page
		$file = "core/forms/settings/".$filename.".form";
		if(file_exists($file)){
		include("core/forms/settings/".$filename.".form");
		}
		
		//Footer
		include(CORE_VIEWS."footer/footer.view");
	}
}
?>