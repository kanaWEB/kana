<?php
include("core/common.inc"); //Common libraries

if($currentUser->isadmin()){

	if(isset($_["menu"])){
		$leftmenu_active = $_["menu"];
	}
	else
	{
		$leftmenu_active = DEFAULT_MENU_SETTINGS;
	}


//POST SETTING
	if(isset($_["submit"])){
		include("core/forms/settings/".$leftmenu_active.".post");
	}
//SETTING FORM
	else
	{	//Header
		include(CORE_VIEWS."header/header.view");
		include(CORE_VIEWS."menu/top.view");
		include(CORE_VIEWS."menu/left.view");
		//Form
		include(CORE_VIEWS."forms/Form.class.php");
		//Page
		include("core/forms/settings/".$leftmenu_active.".form");
		//Footer
		include(CORE_VIEWS."footer/footer.view");
	}
}
?>