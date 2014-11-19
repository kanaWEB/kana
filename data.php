<?php
//We used minimal framework
include("core/constants.inc"); //Global Constants
include("core/schema/SQLKana.class.php");
include("core/schema/Entity.class.php"); //SQL manager

//@todo: Only local script should be able to send data this way
if(!isset($data) && !isset($type)){
if(!isset($_SERVER['REMOTE_ADDR']) || DATA_REMOTE){

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
}
else
{
	echo "nope";
}

}
else
{
	echo "data/type not set, example:&data=1234&type=radio";
}
?>