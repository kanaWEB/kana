<?php
include("core/common.php"); //Common libraries

if($currentUser->isuser()){
	include("core/header.php");
	include("core/topmenu.php");
	include("core/footer.php");
}
else{
	redirect("index","?error=".t("You need to be logged to see this page") );
}

?>