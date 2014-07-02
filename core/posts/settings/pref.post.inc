<?php
if(DEBUG){
	debug("PHP","Variables");
	var_dump($_);
}

//Save Configuration
$config_language = $_["lang"];
$config_theme = $_["theme"];
$config = new Entity("Config");
$config = $config->load(0);
$config->setLanguage($config_language);
$config->setTheme($config_theme);
$config->save();
unset($_SESSION["LANGUAGE"]);
unset($_SESSION['config']);
unset($_SESSION["theme"]);
//Redirect to index
redirect("settings","?menu=pref&notice=".t("Preferences saved"));
?>
