<?php

/**
 * Class Entity.
 *
 * @name: Entity
 * @author: Rémi Sarrailh
 * @description: Parent class for all models (entity) linked to a JSON file,
 */
class JsonEntity
{
    private $object;
    private $id;
    private $file;
    private $dir;
    public $error = false;

    /**
     * Generate Table Schema and open/create database.
     */
    public function __construct($object)
    {
        if (file_exists(USER_OBJECTS.$object)) {
            $this->object = $object;
            $this->dir = PIGET_CONFIG_DIR.$this->object."/";
        }
    }

    public function getById($id)
    {
        $results = false;
        $this->file = $this->dir.$id."/".$this->object.".json";
        if (file_exists($this->file)) {
            $this->id = $id;
            $raw = file_get_contents($this->file);
            $results = json_decode($raw, true);
            $results["id"] = $this->id;
            if (!isset($results["entity_name"])) {
                $results["entity_name"] = "";
                $results["entity_description"] = "";
            }
            return $results;
        }
    }

    public function populate()
    {
        $results = false;
        $ids = Functions::getDirOnly($this->dir);
        //var_dump($ids);
        if ($ids) {
            foreach ($ids as $key => $id) {
                if (file_exists($this->dir.$id."/".$this->object.".json")) {
                    $this->file = $this->dir.$id."/".$this->object.".json";
                    $raw = file_get_contents($this->file);
                    $results[$key] = json_decode($raw, true);
                    $results[$key]["id"] = $id;
                    if (!isset($results[$key]["entity_name"])) {
                        $results[$key]["entity_name"] = "";
                        $results[$key]["uid"] = "";
                        $results[$key]["entity_description"] = "";
                        $results[$key]["uid"] = uniqid();
                    }
                }
            }
        }
        return $results;
    }

    public function delete($id)
    {
        $this->file = $this->dir.$id."/".$this->object.".json";
        if (file_exists($this->file)) {
            if (is_writable($this->file)) {
                unlink($this->file);
                rmdir($this->dir.$id);
            } else {
                $this->error = "Permissions error";
            }
        } else {
            $this->error = "File doesn't exists";
        }

    }

    public function setId($id)
    {
        $this->id = $id;
        $this->file = $this->dir.$this->id."/".$this->object.".json";
        if (file_exists($this->file)) {
            //var_dump("edit mode");
            $file = $this->file;
        }
    }

    public function create()
    {
        //No ID ==> new file
        if (!isset($this->id)) {
            $ids = Functions::getDirOnly($this->dir);
            $ids = array_filter($ids, 'is_numeric');
            $this->id = max($ids) + 1;
            $this->file = $this->dir.$this->id."/".$this->object.".json";
            //var_dump(max($ids));
        }
        //ID ==> replace file
    }

    public function save($array)
    {
        if (isset($this->file)) {
            if (is_array($array)) {
                $array["uid"] = uniqid();
                $json = json_encode($array);
                if (!file_exists($this->dir.$this->id."/")) {
                    mkdir($this->dir."/".$this->id);
                    
                }
                file_put_contents($this->file, $json);
                
            } else {
                $this->error = "NOT AN ARRAY can't save";
            }
        } else {
            $this->error = "NO FILE SET";
        }
    }
}
