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
		include("core/posts/settings/".$leftmenu_active.".post.inc");
	}
//SETTING FORM
	else
	{	//Header
		include("core/header.inc");
		include("core/topmenu.inc");
		include("core/leftmenu.inc");
		//Form
		include("core/functions/Form.class.php");
		//Page
		include("core/forms/settings/".$leftmenu_active.".form.inc");
		//Footer
		include("core/footer.inc");
	}
}
?>