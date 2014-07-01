<?php
//Include template
include EXTERNAL_LIB."RainTpl/rain.tpl.class.php";
raintpl::$cache_dir = CACHE_DIR;
raintpl::$tpl_dir = "";
raintpl::configure( 'path_replace', false );

$tpl = new RainTPL();

//Generate Template
$tpl->assign("lang",$lang);
$tpl->assign("APP_NAME",APP_NAME);
$tpl->assign("VERSION",VERSION);
$tpl->draw(CORE_TEMPLATE."header");

?>
