<?php
include("core/common.inc"); //Common libraries

//@todo verify user right for each views
if($currentUser->isuser()){
	include("core/views/header/header.data");

	if(isset($_["view"])){
	$view_dir = USER_VIEWS."/".$_["view"];
	if(file_exists($view_dir."/view")){
		include("core/views/menu/top.data");
		include($view_dir."/view");
	}
	}
	else
	{
		redirect("views","?view=system");
	}

	include("core/views/footer/footer.data");
}
else{
	redirect("index","?error=".t("You need to be logged to see this page") );
}

?>