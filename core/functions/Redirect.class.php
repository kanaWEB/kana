<?php
/**
 * @todo Documented
**/
function redirect($page,$args=false,$message=false){
	if (DEBUG == false){
				header('location:'.$page.'.php');
			}
			else{
				debug("PHP","Redirection",$page);
			}
	

}