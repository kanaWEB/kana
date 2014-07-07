<?php
include("core/common.inc"); //Common libraries

//@todo verify user right for each views
if($currentUser->isuser()){
	include("core/views/header/header.view");

	if(isset($_["view"])){
	$view_name = $_["view"];
	$view_dir = USER_VIEWS."/".$view_name."/";
	$view_file = $view_dir.$view_name.".view";
	$data_path = USER_DATAS.$view_name."/";
	if(file_exists($view_file)){
		include(CORE_VIEWS."/menu/top.view");
		include($view_dir."/".$view_name.".view");
	}
	}
	else
	{
		redirect("views","?view=system");
	}

	include("core/views/footer/footer.view");
}
else{
	redirect("index","?error=".t("You need to be logged to see this page") );
}

?>