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
	$_SESSION["LANGUAGE"] = $config->getLanguage();
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
	$lang_file = file("core/libraries/Locale.txt");
	
	foreach($lang_file as $language_available){
		$lang_line = explode(" ",$language_available);
		$lang_list[] = [
		"value" => $lang_line[0],
		"text" => trim($lang_line[1])
		];
	}
	return $lang_list;
}

?>