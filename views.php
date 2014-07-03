<?php
include("core/common.inc"); //Common libraries

if($currentUser->isuser()){
	include("core/views/header/header.data");
	include("core/views/menu/top.data");
	include("core/views/footer/footer.data");
}
else{
	redirect("index","?error=".t("You need to be logged to see this page") );
}

?>