<?php
/**
 * Global Constant file
 * You shouldn't need to modify theses
*/
//APP PATH
define('CORE_TEMPLATE',"core/templates/");
define('USER_TEMPLATE',"templates/");
define('CORE_SCHEMA',"core/schema/");

//RAINTPL
define('CACHE_DIR',"cache/");
define('EXTERNAL_LIB',"functions/external/");

//APP INFO
define('APP_NAME',"KANA");
define('VERSION',"0.01");
define('APP_STATE',"ALPHA");

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