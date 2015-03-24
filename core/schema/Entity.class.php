<?php
/**
 * Class Entity
 * @name: Entity
 * @author: Rémi Sarrailh
 * @description: Parent class for all models (entity) linked to the SQLite database,
 */
class Entity extends SQLKana
{
	/**
	 * Generate Table Schema and open/create database
	 */
	function __construct($type,$entity){
		
		//var_dump($type);
		//var_dump($entity);
		
		switch($type){
			case 'core': //You can only create table inside kana.db (core) if there is a corresponding schema.txt inside /core/schema/schema.txt
			$this->database = DATABASE;
			$this->setCoreTable($entity);
			break;

			case 'data': //Check needed (Is there a sensor linked to this data?)
			$this->database = DATA_DIR.$entity.".db";
			//var_dump($this->database);
			//var_dump($entity);
			$this->setDataTable($entity);
			if(file_exists("plugins/sensors/".$entity)){
				$this->check = "plugins/sensors/".$entity;
				//var_dump("sensors");
			}
			else
			{
				$this->check = "plugins/objects/".$entity;
				//var_dump("collectors");
			}
			break;

			case 'config': //Check OK
			$dir = Variable::sensors_or_objects_dir($entity);
			$this->database = CONFIG_DIR.$dir.$entity.".db";
			$this->setConfigTable($entity,$dir);
			$this->check = "plugins/".$dir.$entity;
			break;

			case 'electronics': //Check needed (Is there an objects)
			$this->database = CONFIG_DIR."objects/".$entity.".db";
			$this->setElectronicTable($entity);
			$this->check = USER_OBJECTS.$entity;
			break;

			case 'actions': //Check needed (Is there an objects)
			$this->database = CONFIG_DIR."objects/".$entity.".db";
			$this->setActionTable($entity);
			$this->check = USER_OBJECTS.$entity;
			break;
		}

		//var_dump($this->database);

		//@todo Remove Debug, test performance
		//Generation of database are handled automatically if theses conditions is filled
		//1. If the database doesn't already exists
		//2. If the database 
		if(file_exists($this->database)){
			$this->open($this->database);
			$this->busyTimeout(5000);
		}
		else
		{

			if(file_exists($this->check)){
				$this->open($this->database);
				$this->busyTimeout(5000);
				$this->create();
			}
			else
			{
				echo $this->check;
				echo "ERROR DATABASE SHOULD NOT BE CREATED";
				exit();
			}
		}
	}

	//Set Table fields with CORE SCHEMA file
	function setCoreTable($table_name){
		
		$core_dir = CORE_SCHEMA.$table_name."/".$table_name.".txt";

		if(!file_exists($core_dir)){
			echo "Schema ".$core_dir." doesn't exists!";
			exit();
		}

		$db_text = file($core_dir);
		array_shift($db_text); //Remove first line (description of schema)

		$db_fields['id'] = "key";
		$db_fields['uid'] = "int";

		foreach($db_text as $field){
			$field_info = explode(":",$field);
			$db_fields[$field_info[0]] = $field_info[1];
		}

		if(DEBUG){
			echo "Generating table";
			var_dump("CORE Table :".$table_name." --> ".DATABASE);
			echo "Table fields";
			var_dump($db_fields);
		}

		$this->object_fields = $db_fields;
		$this->TABLE_NAME = $table_name;
		//var_dump($db_fields);
	}

	//Set Table fields with electronics or gpios MD forms
	//You can set the db fields type directly insinde md forms
	function setConfigTable($object_name,$master_dir){
		$db_fields['id'] = "key";
		$db_fields['uid'] = "int";
		$db_fields['entity_name'] = "string";
		$db_fields['entity_description'] = "string";

		if(is_dir(USER_OBJECTS.$object_name."/codes/")){
			$dir = USER_OBJECTS.$object_name."/codes/";
			$inputs_file = Functions::getdir($dir);
		}
		elseif(is_dir(USER_OBJECTS.$object_name."/gpios/")){
			$dir = USER_OBJECTS.$object_name."/gpios/";
			$inputs_file = Functions::getdir($dir);
		}
		else{
			$inputs_file = false;
		}

		if($inputs_file){
			$this->md2fields($dir,$db_fields,$inputs_file);
		}
		else{
			$db_fields['sensor_id'] = "string";
			$db_fields['group_key'] = "string";
			$this->object_fields = $db_fields;
		}

		if(DEBUG){
			echo "Generating table";
			var_dump("CONFIG Table :".$object_name." --> ".CONFIG_DIR.$master_dir.$object_name.".db");
			echo "Table fields";
			var_dump($db_fields);
		}

		$this->TABLE_NAME = "Config";
	}


	//Set Electronics table schema from forms
	function SetElectronicTable($object_name){
		if(is_dir(USER_OBJECTS.$object_name."/electronics/")){
		$dir = USER_OBJECTS.$object_name."/electronics/";
		$inputs_file = Functions::getdir($dir);
		$db_fields['id'] = "key";
		$db_fields['uid'] = "int";

		$this->md2fields($dir,$db_fields,$inputs_file);
		if(DEBUG){
			echo "Generating table";
			var_dump("CONFIG Table :".$object_name." --> ".CONFIG_DIR."objects/".$object_name.".db");
			echo "Table fields";
			var_dump($db_fields);
		}


		}
		else{
			$inputs_file = false;
		}
		$this->TABLE_NAME = "Electronics";
	}

	function setActionTable($object_name){
		$core_dir = CORE_SCHEMA."Actions/Actions.txt";
		$db_text = file($core_dir);
		array_shift($db_text); //Remove first line (description of schema)

		$db_fields['id'] = "key";
		$db_fields['uid'] = "int";
		$db_fields['entity_name'] = "string";
		$db_fields['entity_description'] = "string";

		foreach($db_text as $field){
			$field_info = explode(":",$field);
			$db_fields[$field_info[0]] = trim($field_info[1]);
		}

		if(DEBUG){
			echo "Generating table";
			var_dump("ACTIONS Table : Actions --> ".CONFIG_DIR."objects/".$object_name.".db");
			echo "Table fields";
			var_dump($db_fields);
		}

		$this->object_fields = $db_fields;
		$this->TABLE_NAME = "Actions";
	}

	function setDataTable($db_name){
		$db_fields = array(
			"id"   => "key",
			"data_id" => "text",
			"data" => "text",
			"timestamp" => "int",
			"state" => "text"
			);

		if(DEBUG){
			echo "Generating table";
			var_dump("DATA Table :Data --> ".DATA_DIR.$db_name.".db");
			echo "Table fields";
			var_dump($db_fields);
		}

		$this->object_fields = $db_fields;
		$this->TABLE_NAME = "Data";
	}

	function md2fields($dir,$db_fields,$inputs_file){
		foreach($inputs_file as $input_file){
			$input = Variable::md2var($dir.$input_file);
			if(isset($input["dbtype"])){
				$db_fields[$input["id"]] = $input["dbtype"];
			}
			else{
				$db_fields[$input["id"]] = "TEXT";
			}

		}
		$this->object_fields = $db_fields;
	}

}
?>
