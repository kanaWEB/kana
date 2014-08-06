<?php
//We used minimal framework
include("core/constants.inc"); //Global Constants
include("core/schema/Entity.class.php"); //SQL manager

//@todo: Only local script should be able to send data this way

$collected = $_GET["data"];
$type = $_GET["type"];

$collector_file = "plugins/objects/".$type."/".$type.".collector";
if(file_exists($collector_file)){
include ("core/common.inc");
include("plugins/objects/".$type."/".$type.".collector");
}
else
{
	echo "No collector available inside ".$collector_file;
}
?>