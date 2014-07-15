<?php
include("core/common.inc"); //Common libraries

if(REINSTALL){unlink(DATABASE);redirect("install");}

if(DB_EXISTS){
	if(isset($_["logout"]) || isset($_["submit"])){

		include("core/forms/index.post"); //Load Post
	}
	else
	{
	
		include("core/forms/index.form"); //Load Form
	}		
}
else{
	redirect("install");
}
?>
