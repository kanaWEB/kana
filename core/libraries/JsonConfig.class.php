<?php

/**
 * Class Entity.
 *
 * @name: Entity
 * @author: Rémi Sarrailh
 * @description: Parent class for all models (entity) linked to a JSON file,
 */
class JsonConfig
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

   

    public function populate()
    {
        $results = false;
        if (file_exists($this->dir."/".$this->object.".json")) {
            $this->file = $this->dir."/".$this->object.".json";
            $raw = file_get_contents($this->file);
            $results = json_decode($raw, true);
        }
        return $results;
    }

    public function delete()
    {
        $this->file = $this->dir."/".$this->object.".json";
        if (file_exists($this->file)) {
            if (is_writable($this->file)) {
                unlink($this->file);
            } else {
                $this->error = "Permissions error";
            }
        } else {
            $this->error = "File doesn't exists";
        }

    }

    public function create()
    {
            $this->file = $this->dir.$this->object.".json";
    }

    public function save($array)
    {
        if (isset($this->file)) {
            if (is_array($array)) {
                $json = json_encode($array);
                
                //Create directory if it doesn't exists
                if (!file_exists($this->dir."/")) {
                    mkdir($this->dir."/");
                }

                //Write file if it is writable
                if ((is_writable($this->file)) || (!file_exists($this->file))) {
                    var_dump($this->file);
                    file_put_contents($this->file, $json);
                } else {
                    $this->error = "Permissions error";
                }
                
            } else {
                $this->error = "NOT AN ARRAY can't save";
            }
        } else {
            $this->error = "NO FILE SET";
        }
    }
}
