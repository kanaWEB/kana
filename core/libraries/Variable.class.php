<?php

class Variable
{
    public static function sensors_or_objects_dir($entity)
    {
        if (file_exists(USER_OBJECTS.$entity)) {
            $dir = 'objects/';
        }
        if (file_exists(USER_SENSORS.$entity)) {
            $dir = 'sensors/';
        }
        //if (!isset($dir)){$dir = false;};
        return $dir;
    }

    public static function navtab_item($category, $text, $link, $menu = false)
    {
        if ($menu) {
            $link_begin = 'settings.php?category='.$category.'&menu='.$menu.'&tab=';
        } else {
            $link_begin = 'settings.php?category='.$category.'&tab=';
        }

        $navtab_item = [
        'text' => $text,
        'link' => $link_begin.$link,
        ];

        return $navtab_item;
    }

//Searching a data everywhere we can
    public static function data_dir($data_link)
    {
        $data_link_array = explode('/', $data_link);

        if (count($data_link_array) != 1) {
            $plugin = $data_link_array[0];
            $file = $data_link_array[1];
            if ($plugin == 'object') {
                return $file;
            }
        } else {
            $plugin = $data_link;
            $file = $data_link;
        }

//You can prioritize similar named data here, this can be used to override how a data inside core works.
//@todo data is invariable (unlike donnee in french)
        $data_files[] = USER_DATA.$data_link.'.data'; //We see if there are user defined data
        $data_files[] = USER_VIEWS.$plugin.'/data/'.$file.'.data'; //We see if a view has data
        $data_files[] = USER_OBJECTS.$plugin.'/data/'.$file.'.data'; //We see if objects has data
        $data_files[] = CORE_DATA.$data_link.'.data'; //Finally we check if core has data

        //If we are in debug mode dump file path array
        if (DEBUG) {
            echo 'Searching Data files...';
            var_dump($data_files);
        }

        //Search for data files inside directories
        foreach ($data_files as $data_file) {
            if (file_exists($data_file)) {
                return $data_file;
            }
        }
    }

//Get a data from a data file, you can use a modifiers array to add variables to the data
//@todo verify if it is use everywhere
//For now most data works with global variable and generate a lot of variables that is not required by the application
    public static function get_data($data_link, $modifiers = false)
    {
        //We search every data directory
        $data_file = self::data_dir($data_link);
        //var_dump($data_file);
        //If a modifier is set we convert it into a variable for the data
        if ($modifiers) {
            $variable_name = array_keys($modifiers);
            foreach ($modifiers as $key => $modifier) {
                //var_dump($key);
                //var_dump($modifier);
                $$key = $modifier;
                //var_dump($sensor_type);
                //var_dump($object_name);
            }
        }

        //var_dump($data_file);

        //@todo Security test needed
        //If a file is founded include it
        if (file_exists($data_file)) {
            include $data_file;
        } else {
            //If a file is not founded
            if (file_exists(USER_OBJECTS.'/'.$data_file)) {
                $object_name = $data_file;
                include CORE_DATA.'object.data';
            }
        }

        if (isset($data)) {
            return $data;
        } else {
            return false;
        }
    }

/*

Fields

 */

    //Get all actions arguments from json
    public static function actions_args($actions)
    {
        foreach ($actions as $key_action => $action) {
            $args = json_decode(html_entity_decode($action['args']));
            foreach ($args as $key_arg => $arg) {
                $actions[$key_action][$key_arg] = $arg;
            }
            unset($actions[$key_action]['args']);
        }

        return $actions;
    }

    //Get action arguments from json
    public static function action_args($action)
    {
        $args = json_decode(html_entity_decode($action['args']));
        foreach ($args as $key_arg => $arg) {
            $action[$key_arg] = $arg;
        }
        unset($action['args']);

        return $action;
    }

//Markdown to inputs //JSON is going to be used so this will be deprecated
    public static function md2var($file)
    {
        $input_data = file($file);
        $variables = explode('|', $input_data[0]);
        $variables = array_map('trim', $variables);
        $values = explode('|', $input_data[2]);
        $values = array_map('trim', $values);
        $input = array_combine($variables, $values);

        return $input;
    }

//Markdown to array //JSON is going to be used so this will be deprecated
    public static function md2vars($file)
    {
        $input_data = file($file);
        foreach ($input_data as $key => $data) {
            $variables = explode('|', $input_data[$key]);
            $variables = array_map('trim', $variables);
            if (count($variables) > 1) {
                if ($variables[1] == 'repo') {
                    //@todo Refactor markdown reader for error handling
                    $values = explode('|', $input_data[$key + 2]);
                    $values = array_map('trim', $values);
                    array_shift($variables);
                    array_pop($variables);
                    array_shift($values);
                    array_pop($values);
                    $inputs[] = array_combine($variables, $values);
                }
            }
        }
    //var_dump($inputs);
        return $inputs;
    }

//Availables Actions menu item (Triggers)
    public static function md2menuitem($category, $object_name, $available_md_item)
    {
        if ($category == 'scenario') {
            $link = $_SERVER['SCRIPT_NAME'].'?category='.$category.'&menu=triggers&tab='.$object_name.'&trigger='.$available_md_item;
            $dir = 'triggers';
        } else {
            $link = $_SERVER['SCRIPT_NAME'].'?category='.$category.'&menu='.$object_name.'&tab=action&action='.$available_md_item;
            $dir = 'actions';
        }

        $md_dir = USER_OBJECTS.$object_name.'/'.$dir.'/'.$available_md_item.'/info/';

        //Get name of actions/triggers (internationalized)
        $mdfile_name = $md_dir.'text.md';
        $mdfile_name_translated = $md_dir.'text.'.$_SESSION['LANGUAGE'].'.md';

        //Get Description file (internationalized)
        $mdfile_description = $md_dir.'description.md';
        $mdfile_description_translated = $md_dir.'description.'.$_SESSION['LANGUAGE'].'.md';

        if (file_exists($mdfile_name_translated)) {
            $text = file_get_contents($mdfile_name_translated);
            $description = file_get_contents($mdfile_description_translated);
        } else {
            if (file_exists($mdfile_name)) {
                $text = file_get_contents($mdfile_name);
            } else {
                $text = $mdfile_name;
            }
            if (file_exists($mdfile_description)) {
                $description = file_get_contents($mdfile_description);
            } else {
                $description = $mdfile_description;
            }
        }

        $menu_item = [
            'text' => $text,
            'description' => $description,
            'link' => $link,
            'dir' => $available_md_item,
        ];

        return $menu_item;
    }

//Which menu has an object
    public static function object_menus_name($object_name)
    {
        $path = USER_OBJECTS.$object_name;
        if (file_exists($path.'/actions')) {
            $menu_name['actions'] = true;
        }
        if (file_exists($path.'/help')) {
            $menu_name['help'] = true;
        }

        if (file_exists($path.'/gpios')) {
            $menu_name['gpios'] = true;
        }

        if (file_exists($path.'/electronics')) {
            $menu_name['electronics'] = true;
        }

        if (file_exists($path.'/codes')) {
            $menu_name['codes'] = true;
        }

        if (!isset($menu_name)) {
            $menu_name = false;
        }

        return $menu_name;
    }

/*

REFACTORING IN PROGRESS
EVERYTHING RELATED TO WIDGETS VARIABLE AND DRAWING WILL BE MOVED INSIDE WIDGET.CLASS.PHP FOR SIMPLIFICATION

actions_to_webobjects($object,$actions_list);
ANALYSE
Draw.class.php L111
$webobjects = Variable::actions_to_webobjects($object_type,$actions_list);


objects_to_webobjects($current_group);
sensors_to_websensors($current_group);

Widget need

*/

    public static function actions_to_webobjects($object, $actions_list)
    {
        $key = 0;
        $webobjects = [];

//If there are actions
        if ($actions_list) {
            foreach ($actions_list as $action) {
                if (file_exists(USER_OBJECTS.$object.'/actions/'.$action['action'].'/'.'buttons.json')) {
                    $pathCustom = "/user/config/kana/objects/";
                    //Define id/widget/type/name/description/icon

                    //Get commands information from database
                    $webobjects[$key]['info']['id'] = $action['id'];
                    $webobjects[$key]['info']['object_key'] = $action['object_key'];
                    $webobjects[$key]['info']['uid'] = $action['uid'];
                    $webobjects[$key]['info']['command'] = $action['action'];
                    $webobjects[$key]['info']['object'] = $object;

                    //Get widgets information
                    $webobjects[$key]['info']['name'] = htmlspecialchars_decode($action['entity_name']);
                    $webobjects[$key]['info']['description'] = htmlspecialchars_decode($action['entity_description']);
                    $webobjects[$key]['info']['icon'] = USER_OBJECTS.$object.'/icon.png';

                    //Get state information
                    //Every widgets have a location where it can provided information on the widget
                    //Each states are specified inside state.json
                    if (file_exists($pathCustom.$object."/".$action['id']."/state.json")) {
                        $state_file_path = $pathCustom.$object."/".$action['id']."/state.json";
                    } else {
                        $state_file_path = USER_OBJECTS.$object.'/actions/'.$action['action'].'/'.'state.json';
                    
                    }
                        $state_file = file_get_contents($state_file_path);
                        $state_json = json_decode($state_file);

                    if ($state_json == null) {
                        var_dump($object." doesn't have a correct state.json for action:".$action['action']);
                        exit();
                    }

                    /*
                        Buttons generator
                    */
                    //Get buttons information
                    //Each buttons are specified inside buttons.json

                    if (file_exists($pathCustom.$object."/".$action['id']."/buttons.json")) {
                        $buttons_file_path = $pathCustom.$object."/".$action['id']."/buttons.json";
                    } else {
                        $buttons_file_path = USER_OBJECTS.$object.'/actions/'.$action['action'].'/buttons.json';
                    }
                    $buttons_file = file_get_contents($buttons_file_path);
                    $buttons_json = json_decode($buttons_file);
                    if ($buttons_json == null) {
                        var_dump($object." doesn't have a correct buttons.json for action:".$action['action']);
                        exit();
                    }
                    $webobjects[$key]['buttons'] = $buttons_json->buttons;

                    //$webobjects[$key]["buttons"] = $buttons_json;
                    $webobjects[$key]['state'] = $state_json;

                    //There are 2 ways to known the state of an object
                    //1: onload check a data onload (to use when status doesn't take time to load)
                    //2: user check a data when a user asked for the state (by clicking check or check all)
                    if (isset($state_json->onload)) {
                        $modifiers['state_action'] = $action;
                        $webobjects[$key]['state_style'] = self::get_data($state_json->onload, $modifiers);
                    } else {
                        $webobjects[$key]['state_style'] = [
                        'text' => t('Unknown'),
                        'class' => 'state_unknown',
                        ];
                    }
                    ++$key;
                } else {
                    var_dump($object." doesn't have action:".$action['action']);
                }
            }
        }

        return $webobjects;
    }

    public static function objects_to_webobjects($current_group)
    {
        $objects = Functions::getdir(USER_OBJECTS);
        $webobjects = [];
        //For each objects
        foreach ($objects as $object) {
            //Get actions from objects
            $actions_db = new Entity('actions', $object);
            $actions_list = $actions_db->loadAll([
            'group_key' => $current_group,
            ]);

            //We get every webobjects of a type and merge it with the complete list of webobjects
            $webobjects_tmp = self::actions_to_webobjects($object, $actions_list);

            //var_dump($webobjects_tmp);
            $webobjects = array_merge($webobjects, $webobjects_tmp);
        }

        //var_dump($webobjects);
        if (isset($webobjects)) {
            return $webobjects;
        } else {
            return false;
        }
    }

    public static function sensors_to_websensors($current_group = false)
    {
        $sensors_type = Functions::getdir(USER_SENSORS);
        $key = 0;
        foreach ($sensors_type as $sensor_type) {
            $sensors_objects_db = new Entity('config', $sensor_type);
            if ($current_group) {
                $sensors_list = $sensors_objects_db->loadAll([
                    'group_key' => $current_group,
                    ]);
            } else {
                $sensors_list = $sensors_objects_db->populate();
            }

            //var_dump($sensors_list);

            if ($sensors_list) {
                $sensors_db = new Entity('core', 'Sensors');
                foreach ($sensors_list as $sensor_object) {
                    $websensors[$key] = $sensors_db->load([
                    'sensor_id' => $sensor_object['sensor_id'],
                    ]);
                    if ($websensors[$key]) {
                        $websensors[$key]['name'] = $sensor_object['entity_name'];
                        $websensors[$key]['description'] = $sensor_object['entity_description'];
                        $websensors[$key]['icon'] = USER_SENSORS.$websensors[$key]['sensor_type'].'/icon.png';

                        $websensors[$key]['timesince'] = Functions::time_since_smaller($websensors[$key]['sensor_timestamp']);

                        if ($websensors[$key]['sensor_battery'] != 'ON') {
                            $battery_level = intval($websensors[$key]['sensor_battery']);
                            if ($battery_level < 30) {
                                $websensors[$key]['sensor_battery_class'] = 'danger';
                            }

                            if ($battery_level > 30 && $battery_level < 60) {
                                $websensors[$key]['sensor_battery_class'] = 'warning';
                            }

                            if ($battery_level > 60) {
                                $websensors[$key]['sensor_battery_class'] = 'success';
                            }
                    //var_dump($battery_level);
                        } else {
                            $websensors[$key]['sensor_battery_class'] = 'info';
                        }

                        ++$key;
                    }
                }
            }
        }
        if (isset($websensors)) {
            if (!$websensors[0]) {
                return false;
            } else {
                return $websensors;
            }
        } else {
            return false;
        }
    }
}
