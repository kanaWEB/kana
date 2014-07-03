<?php
/**
 * @todo Redirect to another page
**/
function redirect($page,$args=false){
	$page = $page.".php".$args;
	if (DEBUG == false){
				header('location:'.$page);
			}
			else{
				debug("PHP","Redirection",$page,true);
			}
}