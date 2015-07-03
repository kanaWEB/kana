<?php
/**
 * Global Constant file
 * Modifying this file can deeply change how Kana will works and alter security
*/
//CORE PATH
define('CORE_TEMPLATES', "core/templates/"); //Where RainTPL template are stored
define('CORE_SCHEMA', "core/schema/"); //Where core database schema and libraries are stored
define('CORE_DATA', "core/data/"); //core Data generator
define('CORE_FORMS', "core/forms/"); //core/users Form generator

//DATABASE PATH 
//Personal data are not store on the web server directory 
//so we avoid leaking personal information accidently
define('DATABASE', '/opt/user/config/kana/kana.db'); //The main database
define('DATA_DIR', "/opt/user/data/kana/"); //Data from sensors and collectors
define('CONFIG_DIR', "/opt/user/config/kana/"); //Configuration are saved here in one database for one plugins
//The main folder for personal data 
define('KANA_DIR', "/opt/user/data/kana/");

//RAINTPL
define('CACHE_DIR', "cache/");

//EXTERNAL DEPENDENCIES, Every dependancies are inside this directory.
define('EXTERNAL_LIB', "core/libraries/dependancies/");

//You can redefined where are installed plugins here
define('USER_THEME', "plugins/theme/");
define('USER_VIEWS', "plugins/views/");
define('USER_DATA', "plugins/data/");
define('USER_ACTIONS', "plugins/actions/");
define('USER_OBJECTS', "plugins/objects/");
define('USER_SENSORS', "plugins/sensors/");

define('LANGUAGE', "plugins/language/");

//Application information, 
define('APP_NAME', "KANA");
define('VERSION', "0.42");
define('APP_STATE', "ALPHA");
define('AUTHOR', "MadNerds");

//Get page name used for redirection
define('PHP_PAGE', basename($_SERVER['PHP_SELF']));

//Collector stored everything they received until this point where the data are flush.
define('MAXDATA', 10);

//Very insecure!!
define('DATA_REMOTE', true);
define('SQL_PREFIX', '');
define('DB_EXISTS', file_exists(DATABASE));

//COOKIE
define('COOKIE_LIFE', '355');
define('COOKIE_NAME', 'KANA');

//DEBUG
define('DEBUG', false);
define('PUSH', false);
define('SHOWCOMMANDS', true);
define('DEVMODE', true);
define('REINSTALL', false);
define('INSTALL_THEME', "bootstrap");


//MENU
define('DEFAULT_CATEGORY_SETTINGS', "objects");
define('DEFAULT_MENU_SETTINGS', "allobjects");


//GPIO : wPi / BCM / Name
//define('DEFAULT_GPIO',"wPi");
define('DEFAULT_GPIO', "wPi");
