<?php
include("core/common.inc");

if($currentUser->isuser()){
	//@todo check user right to do actions
	if(isset($_["action"])){
		$action = $_["action"];

		switch($action){
			case "ajax":
				$data_link = $_["data"];
				include(USER_DATAS.$data_link.".data");
			break;
		}
	}
}

?>