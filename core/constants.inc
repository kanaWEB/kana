<?php
/**
 * Global Constant file
 * You shouldn't need to modify theses
*/
//CORE PATH
define('CORE_VIEWS',"core/views/");
define('CORE_SCHEMA',"core/schema/");
define('CORE_DATA',"core/data/");
define('CORE_WIDGETS',"core/widgets/");
define('CORE_FORMS',"core/forms/");

//DATABASE PATH
define('DATABASE','/etc/kana/kana.db');
define('DATA_DIR',"/etc/kana/data/");
define('CONFIG_DIR',"/etc/kana/config/");

//RAINTPL
define('CACHE_DIR',"cache/");

//EXTERNAL DEPENDENCIES
define('EXTERNAL_LIB',"core/libraries/dependancies/");

//USER PATH
define('USER_THEME',"plugins/theme/");
define('USER_VIEWS',"plugins/views/");
define('USER_DATA',"plugins/data/");
define('USER_ACTIONS',"plugins/actions/");
define('USER_OBJECTS',"plugins/objects/");

define('LANGUAGE',"plugins/language/");

//APP INFO
define('APP_NAME',"KANA");
define('VERSION',"0.40");
define('APP_STATE',"ALPHA");
define('AUTHOR',"MadNerds");
define('PHP_PAGE',basename($_SERVER['PHP_SELF']));


//DATA
define('MAXDATA',10);

//Very insecure!!
define('DATA_REMOTE',true);
define('SQL_PREFIX','');
define('DB_EXISTS',file_exists(DATABASE));

//COOKIE
define('COOKIE_LIFE','355');
define('COOKIE_NAME','KANA');

//DEBUG
define('DEBUG',false);
define('PUSH',true);
define('SHOWCOMMANDS',false);
define('DEVMODE',true);
define('REINSTALL',FALSE);
define('INSTALL_THEME',"bootstrap");

//MENU
define('DEFAULT_CATEGORY_SETTINGS',"objects");
define('DEFAULT_MENU_SETTINGS',"allobjects");


//GPIO : wPi / BCM / Name
//define('DEFAULT_GPIO',"wPi");
define('DEFAULT_GPIO',"wPi");


?>