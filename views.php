<?php
include("core/common.inc"); //Common libraries

//If the user is set
if(isset($currentUser)){

//If the user is not disable
if($currentUser->isuser()){
	include(CORE_VIEWS."/header/header.view");

	//If a view is set
	if(isset($_["view"])){
		$view_name = $_["view"];
		$view_right = $currentUser->ViewRight($view_name);
		

		if($view_right){
			$view_dir = USER_VIEWS."/".$view_name."/";
			$view_file = $view_dir.$view_name.".view";
			$data_path = USER_DATA.$view_name."/";
			$md_file = $view_dir.$view_name.".md";

			//Show a button to make view as default only if it isn't already the case
			if($currentUser->default_view() != $view_name){
				$default_view_button = [
				"view" => $view_name,
				"iduser" => $currentUser->id(),
				];
				$tpl->assign("button",$default_view_button);
			}
			else{
				$default_view_button = False;
			}
			
		//VIEW FILE (php code mode)
			if(file_exists($view_file)){
				include(CORE_VIEWS."/menu/top.view");
				if($default_view_button != False) {$tpl->draw(CORE_VIEWS."buttons/default_view");}
				include($view_file);
			}
		//VIEW MD TABLE (markdown mode)
			elseif(file_exists($md_file)){
				include(CORE_VIEWS."/menu/top.view");
				if($default_view_button != False) {$tpl->draw(CORE_VIEWS."buttons/default_view");}
				$blocks = Draw::md2datatable($md_file,$view_dir);
				
				include(CORE_VIEWS."buttons/ajax_play.view");
				include(CORE_VIEWS."datatable/blocks.view");
			}
		}
		else{
				//Root hasn't got any access to the views?, show the admin user page
				if($currentUser->isadmin()){
					redirect("settings","?category=configuration&menu=users&id=1&notice=".t("You must set your permissions first"));
				}
				//Else if it's an user, tell them to ask the admin to give him right
				else
				{
				$tpl->draw(CORE_VIEWS."modal/permissions_denied");
				}

			}
		}
		//If no view is set
		else{
			//Redirect to first view
			if($currentUser->default_view() == ""){
				$view_list = Functions::getdir(USER_VIEWS);
				redirect("views","?view=".$view_list[0]);
			}
			else
			{
				//Redirect to 
				redirect("views","?view=".$currentUser->default_view());
			}
		}

		include(CORE_VIEWS."/footer/footer.view");
	}
	else{
		redirect("index","?error=".t("You need to be logged to see this page") );
	}
}
//If no user is set, we assume nothing was installed
else
{
	redirect("install");
}
	?>
