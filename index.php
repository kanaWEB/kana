<?php
include("core/common.inc"); //Common libraries
if(REINSTALL){unlink(DATABASE);redirect("install");}

if(DB_EXISTS){
	if(isset($_["logout"]) || isset($_["submit"])){
		include("core/posts/login.post.inc"); //Load Post
	}
	else
	{
		include("core/forms/login.form.inc"); //Load Form
	}		
}
else{
	redirect("install");
}
?>
