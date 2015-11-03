<?php
//@todo DOC
class Widget
{
    private $type = false; //Is this an object or a sensor ?
    private $list = []; //Data needed to generate a widget
    private $html; //Raw HTML render of all widgets
    private $action_list;

    function __construct($type)
    {
        $this->$type = $type;
    }

    public function getList($format = "raw")
    {
        switch ($format) {
            case "raw":
                return $this->$list;
                break;
            case "json":
                return json_encode($this->list);
                break;
        }
    }

    public function getById($object, $id)
    {
        echo "Not implemented";
    }

    public function getByObjectType($object)
    {
        //Nothing
    }

    public function getBySensorType($sensor)
    {
        
    }

    public function getByGroup($group)
    {
        
        $objets == Functions::getdir(USER_OBJECTS);

        $actions_db = new Entity("actions", $object);
        $actions_list = $actions_db->loadAll([
            "group_key" => $group
        ]);

        $this->list = [];

        if ($actions_list) {
            foreach ($actions_list as $action) {
                if (file_exists(USER_OBJECTS.$object."/actions/".$action["action"]."/"."buttons.json")) {
                    echo "Not implemented";
                }
            }
        }
    }

    public function render($tpl)
    {
        //$html .= $tpl->draw($widget,true);
        $this->html();
    }

    public function draw($tpl)
    {
        $this->render($tpl);
    }
}
