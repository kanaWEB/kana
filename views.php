<?php
include("core/common.inc"); //Common libraries

//@todo verify user right for each views
if($currentUser->isuser()){
	include(CORE_VIEWS."/header/header.view");

	if(isset($_["view"])){
		$view_name = $_["view"];
		$view_dir = USER_VIEWS."/".$view_name."/";
		$view_file = $view_dir.$view_name.".view";
		$data_path = USER_DATAS.$view_name."/";
		$md_file = $view_dir.$view_name.".md";

		if(file_exists($view_file)){
			include(CORE_VIEWS."/menu/top.view");
			include($view_file);
		}
		elseif(file_exists($md_file)){
			include(CORE_VIEWS."/menu/top.view");
			$blocks = Functions::md2datatable($md_file,$view_dir);
			include(CORE_VIEWS."buttons/ajax_play.view");
			include(CORE_VIEWS."datatable/blocks.view");

		}
	}
	else{
		redirect("views","?view=system");
	}

	include(CORE_VIEWS."/footer/footer.view");
}
else{
	redirect("index","?error=".t("You need to be logged to see this page") );
}

?>