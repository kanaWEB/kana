<?php
/* DRAWING FUNCTIONS */

class Draw
{

//Ajax Notification
//If a PHP file is called in ajax, you can make it return a notification
    public static function ajax_notify($text, $type, $data = false)
    {
        $message = [
        "text" => t($text),
        "type" => $type,
        "data" => $data
        ];
        echo json_encode($message);
    //echo $type." !::! ".t($text);
    }


//Move to another functions (CoreDraw::md2datable)
    public static function md2datatable($filename, $datadir)
    {
        $file_array = file($filename);
        $isdata = false;
        $isblock = true;
        $nb = 0;
        foreach ($file_array as $key => $line) {
            if ($isblock) {
                $tr = 0;
                if (trim($line) != "") {
                    $line_array = explode("|", $line);

                    $blocks[$nb] = [
                    "name" => trim($line_array[0]),
                    "icon" => trim($line_array[1])
                    ];
                    $isblock = false;
                }
            } else {
                if ($isdata) {
                //@todo choose between views/data

                    $line_array = explode("|", $line);


                    $plugin = trim($line_array[0]);
                    if ($plugin == "") {
                        $isdata = false;
                        $isblock = true;
                        $nb++;
                    } else {
                        $data_file = trim($line_array[1]);
                        $user_file = USER_VIEWS.$plugin."/data/".$data_file.".view";
                        $core_file = CORE_TEMPLATES.$plugin."/".$data_file.".view";

                        if (file_exists($user_file)) {
                            include($user_file);
                        } elseif (file_exists($core_file)) {
                            $blocks[$nb]["row"][$tr]["custom"] = [
                            "link" => $core_file
                            ];
                        //include($core_file);
                        } else {
                            echo "I can't find ".$user_file." or ".$core_file." !";
                            exit();
                        }

                        $tr++;
                    }
                } else {
                    $isdata = true;
                }
            }

        }
        if (DEBUG) {
            Functions::pretty_debug($blocks);
        }
        return $blocks;

    }

//Move to another functions (CoreDraw::md2help)
    public static function md2help($object_name)
    {

        $Parsedown = new Parsedown();
        $translated_help = USER_OBJECTS.$object_name."/help/help.".$_SESSION["LANGUAGE"].".md";
        $help = USER_OBJECTS.$object_name."/help/help.md";

        //var_dump("reading".$translated_help);

        if (file_exists($translated_help)) {
            $text = file_get_contents($translated_help);
        } else {
            if (file_exists($help)) {
                $text = file_get_contents(USER_OBJECTS.$object_name."/help/help.md");
            }
        }
        echo $Parsedown->text($text);
    }

    public static function object_widget_by_uid($object_type, $object_uid, $tpl)
    {
        $actions_db = new Entity("actions", $object_type);
        $actions_list = $actions_db->loadAll([
            "uid" => $object_uid
        ]);
        $webobjects = Variable::actions_to_webobjects($object_type, $actions_list);
        Draw::object_widget($webobjects, $tpl);
    }

    public static function object_widget_by_type($object_type, $tpl)
    {
        $actions_db = new Entity("actions", $object_type);
        $actions_list = $actions_db->populate();
        $webobjects = Variable::actions_to_webobjects($object_type, $actions_list);
        Draw::object_widget($webobjects, $tpl);
    }

    // @todo Refactor draw objects generation, it should be inside objects.class.php and design should be handle by RainTPL only.
    public static function object_widget($webobjects, $tpl)
    {
        foreach ($webobjects as $key => $webobject) {
                $webobject["widget_id"] = $key;
                $tpl->assign("info", $webobject["info"]);
                $tpl->assign("buttons", $webobject["buttons"]);
                $tpl->assign("state", $webobject["state"]);
                $tpl->assign("state_style", $webobject["state_style"]);
                //var_dump($webobject);
                // $widget_override_path = USER_OBJECTS.$webobject["object"]."/widgets/".$webobject["action"] ."/".$webobject["action"];
                //var_dump($widget_override_path);
                $widget = CORE_TEMPLATES."dashboard/default";
                $tpl->draw(CORE_TEMPLATES."dashboard/panel_widget");
                $tpl->draw($widget);
                $tpl->draw(CORE_TEMPLATES."dashboard/default_state");
                $tpl->draw(CORE_TEMPLATES."dashboard/panel_widget_close");
        }
    }


    //Generate widgets of objects
    public static function objects_widgets($current_group, $tpl)
    {
       
        $webobjects = Variable::objects_to_webobjects($current_group);
        //Functions::pretty_Debug($webobjects);
        //var_dump($webobjects);
        if ($webobjects) {
            //Functions::pretty_debug($webobjects);
            
            //$tpl->draw(CORE_TEMPLATES."grids/row/col-sm-10");
             /*       
            $col = 0;
            $row = 3;
            $widgets_col_mobile = 12;
            $widgets_col_tablet = 6;
            $widgets_col_desktop = 6;
            $widgets_col_large = 4;

*/
            foreach ($webobjects as $key => $webobject) {
                $webobject["widget_id"] = $key;
                //var_dump($webobject);
                $tpl->assign("info", $webobject["info"]);
                $tpl->assign("buttons", $webobject["buttons"]);

                $tpl->assign("state", $webobject["state"]);
                $tpl->assign("state_style", $webobject["state_style"]);

                //var_dump($webobject);
                //$widget_override_path = USER_OBJECTS.$webobject["object"]."/widgets/".$webobject["action"] ."/".$webobject["action"];
                //var_dump($widget_override_path);
                $widget = CORE_TEMPLATES."dashboard/default";

                //If widget is not the default one, override widgets 
                //if(file_exists($widget_override_path.".html")){
                //  $widget = $widget_override_path;
                //}
                //else{
                //  $widget = $widget_default;
                //}


                /*
                if ($col == 0) {

                    ?>

                    <div class="row">
                    <?
                }
                ?>
                    <div class="col-xs-<? echo $widgets_col_mobile ?> col-sm-<? echo $widgets_col_tablet ?> col-md-<? echo $widgets_col_desktop ?> col-lg-<? echo $widgets_col_large ?>  col-ui-sortable">
                <?
                */

                $tpl->draw(CORE_TEMPLATES."dashboard/panel_widget");
                $tpl->draw($widget);
                $tpl->draw(CORE_TEMPLATES."dashboard/default_state");
                
                $tpl->draw(CORE_TEMPLATES."dashboard/panel_widget_close");
                /*
                ?>
                    </div>
                <?

                $col++;

                if($col == $row){
                    $col = 0;
                ?>
                    </div>
                <?
                }
                */
            }
            //$tpl->draw(CORE_TEMPLATES."grids/block/close");
            //$tpl->draw(CORE_TEMPLATES."grids/row/close");
            return true;
        }
        else
        {
            return false;
        }
    }

//Draw all sensors widgets for a specific group
    public static function sensors_widgets($current_group,$tpl){
        $websensors = Variable::sensors_to_websensors($current_group);
        //var_dump($websensors);
        if($websensors){
            $tpl->draw(CORE_TEMPLATES."grids/row/col-sm-2");
            foreach($websensors as $websensor){
                if(file_exists(USER_SENSORS.$websensor["sensor_type"]."/widgets/".$websensor["sensor_type"]."/".$websensor["sensor_type"].".html")){
                $tpl->assign("websensor",$websensor);
                $widget = USER_SENSORS.$websensor["sensor_type"]."/widgets/".$websensor["sensor_type"]."/".$websensor["sensor_type"];
                $tpl->draw($widget);

                }
            }
            $tpl->draw(CORE_TEMPLATES."grids/row/close");
            return true;
        }
        else{
            return false;
        }
    }



//Generate a menu with choice of widgets
    public static function menu_allobjects($tpl){
        $tpl->assign("text","There is nothing in this group, do you want to create an object?");
        $tpl->draw(CORE_TEMPLATES."text/h1");
        $tpl->assign("settingsmenu_active",false);
        include(CORE_TEMPLATES."menu/settings/objects/objects.view");
    }
}
?>