<?php
/*
 @name: functions
 @author: Sarrailh Rémi
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

//If database does not exists (install) autodetect language
if(!file_exists(DATABASE)){
	if (DEBUG) echo "Reloading Language";
	$_SESSION["LANGUAGE"] = get_lang();
}

//If Language session variable is not set, search it inside the database
if (empty($_SESSION["LANGUAGE"])){
	//global $conf;
	//$language_manager = new Language();
	//if($conf){
	//$language = $language_manager->getById($conf->get("general_language"));
	//}
	//print_r($language);

	//if (empty($language)){
//		if (DEBUG) echo "Reloading Language";
		//$_SESSION["LANGUAGE"] = get_lang();
	//}
	//else
	//{
	//$_SESSION["LANGUAGE"] = $language->getShortName();
	//}
}

//Loading Core Translation
global $lang;
if (file_exists("core/lang/".$_SESSION["LANGUAGE"].".php"))
{
	require("core/lang/".$_SESSION["LANGUAGE"].".php");
}

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

?>