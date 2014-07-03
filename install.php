<?php
include("core/common.inc"); //Common libraries

if(!DB_EXISTS){
//POST INSTALL
	if(isset($_["submit"])){
		include("core/forms/install.post");
	}
//INSTALLATION FORM
	else
	{
		include("core/forms/install.form");
	}
}
else
{
	echo "<h1>".t("Installation is already done!")."</h1>";
	echo "<legend>".t("Change this constant to reinstall:")."<legend><br>";
	echo "<b>core/constant.inc</b><br>";
	echo '<code>define("REINSTALL","TRUE")</code>';
}
?>