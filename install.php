<?php
include("core/common.inc"); //Common libraries

if(!DB_EXISTS){
//POST INSTALL
	if(isset($_["submit"])){
		include("core/posts/install.post.inc");
	}
//INSTALLATION FORM
	else
	{
		include("core/forms/install.form.inc");
	}
}
else
{
	echo "<h1>".t("Installation is already done!")."</h1>";
	echo "<legend>".t("Change this constant to reinstall:")."<legend><br>";
	echo "<b>core/config.inc</b><br>";
	echo '<code>define("REINSTALL","TRUE")</code>';
}
?>