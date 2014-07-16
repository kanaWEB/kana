<?php
/*
 @name: functions
 @author: Sarrailh Remi
 @description: Internationalization Support
*/
 
/*
Instructions for translation:
================
PHP :
==
t('ENGLISH');

RAINTPL:
==
{function="t('ENGLISH')"}

HTML:
==
<? echo t("ENGLISH") ?>
*/

/*LIST OF LANGUAGE SUPPORT */
//If Language session variable is not set, search it inside the database
if (!DB_EXISTS){
	$_SESSION["LANGUAGE"] = get_lang();
}
else
{
	$_SESSION["LANGUAGE"] = $config["language"];
}


//Loading Core Translation
global $lang;
$lang = add_language("plugins");


function add_language($dir){
$lang_php = $dir."/"."language/".$_SESSION["LANGUAGE"]."/".$_SESSION["LANGUAGE"].".php";
if (file_exists($lang_php))
{
	include($lang_php);
}
else{
$lang = false;
}
return $lang;
}


//Loading others



/* ----------------------- */
/*
/*Get Language*/
function get_lang()
{
	$lang_browser = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
//Get Language from browser configuration
	$lang_browser = explode(",",$lang_browser);
	$lang_browser = $lang_browser[0];

//Just take the first parts (for different language)
	$lang_browser = explode("-",$lang_browser);
	$lang_browser = $lang_browser[0];
	return $lang_browser;
}

/*Translate $val if it exist*/
function t($val)
{
	if(isset($GLOBALS['lang'][$val]))
	{
		return $GLOBALS['lang'][$val];
	}
	else
	{
		return $val;
	}  
}

function get_langlist(){
	$lang_list[] = [
	"value" => "en",
	"text" => "English"
	];

	$lang_files = Functions::getdir("plugins/language");
	
	foreach($lang_files as $lang_filename){
		$lang_file = file("plugins/language/".$lang_filename."/readme.md");
		$lang_line = explode("|",$lang_file[4]);

		$lang_list[] = [
		"value" => $lang_filename,
		"text" => $lang_line[1]
		];
	}

	return $lang_list;
}

?>