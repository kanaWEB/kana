<?php
include("core/common.inc"); //Common libraries

if($currentUser->isuser()){
	include("core/header.inc");
	include("core/topmenu.inc");
	include("core/footer.inc");
}
else{
	redirect("index","?error=".t("You need to be logged to see this page") );
}

?>