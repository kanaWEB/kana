<?php
/**
 * Global Constant file
 * You shouldn't need to modify theses
*/
//APP PATH
define('CORE_VIEWS',"core/views/");
define('CORE_SCHEMA',"core/schema/");

define('USER_THEME',"plugins/theme");
define('USER_VIEWS',"plugins/views");
define('LANGUAGE',"plugins/language");

//RAINTPL
define('CACHE_DIR',"cache/");
define('EXTERNAL_LIB',"core/libraries/dependancies/");

//APP INFO
define('APP_NAME',"KANA");
define('VERSION',"0.01");
define('APP_STATE',"ALPHA");
define('PHP_PAGE',basename($_SERVER['PHP_SELF']));

//DATABASE
define('DATABASE','/etc/kana/kana.db');
define('SQL_PREFIX','');
define('DB_EXISTS',file_exists(DATABASE));


//COOKIE
define('COOKIE_LIFE','355');
define('COOKIE_NAME','KANA');

//DEBUG
define('DEBUG',FALSE);
define('REINSTALL',FALSE);
define('INSTALL_THEME',"bootstrap");

//MENU
define('DEFAULT_MENU_SETTINGS',"pref")
?>