<?php
include("core/common.inc"); //Common libraries

//@todo verify user right for each views
if(isset($currentUser)){
if($currentUser->isuser()){
	include(CORE_VIEWS."/header/header.view");

	if(isset($_["view"])){
		$view_name = $_["view"];
		$view_right = $currentUser->ViewRight($view_name);
		if($view_right){
			$view_dir = USER_VIEWS."/".$view_name."/";
			$view_file = $view_dir.$view_name.".view";
			$data_path = USER_DATAS.$view_name."/";
			$md_file = $view_dir.$view_name.".md";


			//Show a button to make view as default only if it isn't already the case
			if($currentUser->default_view() != $view_name){
				$default_view_button = [
				"view" => $view_name,
				"iduser" => $currentUser->id(),
				];
				$tpl->assign("button",$default_view_button);
			}
			else
			{
				$default_view_button = False;
			}
			

		//VIEW FILE
			if(file_exists($view_file)){
				include(CORE_VIEWS."/menu/top/top.view");
				if($default_view_button != False) {$tpl->draw(CORE_VIEWS."buttons/default_view");}
				include($view_file);

			}
		//VIEW MD TABLE
			elseif(file_exists($md_file)){
				include(CORE_VIEWS."/menu/top/top.view");
				if($default_view_button != False) {$tpl->draw(CORE_VIEWS."buttons/default_view");}
				$blocks = Draw::md2datatable($md_file,$view_dir);
				include(CORE_VIEWS."buttons/ajax_play.view");
				include(CORE_VIEWS."datatable/blocks.view");
			}
		}
		else{
			$views_right = $currentUser->ViewsRight();
			if($views_right){
				redirect("views","?view=".$views_right[1]."&error=".t("Permissions denied by Administrator"));
			}
			else
			{
				if($currentUser->right() == 1){
					redirect("settings");
				}
				$tpl->draw(CORE_VIEWS."modal/permissions_denied");

				}

			}
		}
		else{
			//Default view
			if($currentUser->default_view() == ""){
				$view_list = Functions::getdir(USER_VIEWS);
				redirect("views","?view=".$view_list[0]);
			}
			else
			{
				redirect("views","?view=".$currentUser->default_view());
			}
		}

		include(CORE_VIEWS."/footer/footer.view");
	}
	else{
		redirect("index","?error=".t("You need to be logged to see this page") );
	}
}
else
{
	redirect("install");
}
	?>
