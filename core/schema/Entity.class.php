<?php
/**
 * Class Entity
 * @name: Entity
 * @author: Idleman <idleman@idleman.fr>
 * @description: Parent class for all models (entity) linked to the SQLite database,
 * This class is configured to act like a SQLite database,
 * but it is possible to redefine SQL codes to adapt them to another SGBD without affecting the application
 */
class Entity extends SQLite3
{
    /**
     * @var bool Debug mode, display SQL CREATE/UPDATE/DELETE commands when they are executed.
     */
    private $debug = false;
    private $object_table = false;

	/**
	 * Open an SQLite connection
	 */
	//function __construct($table_name,$user=false){	
	function __construct($table_name,$dbfields=false){	

		$user_dir = USER_OBJECTS.$table_name."/".$table_name.".txt";
		$core_dir = CORE_SCHEMA.$table_name."/".$table_name.".txt";

		//User schema
		if(file_exists($user_dir)){
			$this->object_table = true;
			$this->setTable($table_name,$user_dir);
		}
		//Core Schema
		else if(file_exists($core_dir)){
			$this->setTable($table_name,$core_dir);
		}
		else{
			if($dbfields){
				$this->setCustomTable($table_name,$dbfields);
			
			}
			else
			{
				var_dump($table_name." NOT FOUNDED");
				var_dump($user_dir);
				var_dump($core_dir);
				var_dump($dbfields);
				exit();
			}
		}

		if(!isset($notfound)){
		$this->open(DATABASE);
		$this->busyTimeout(5000); //Wait 5 seconds (avoid multiples connection to fail)
		if (DEBUG == true) {$this->debug = true;} //See if DEBUG in constant.php is set.
		$this->create(); //Create the table of the entity if it doesn't exists
		}
	}

	/**
	 * Auto Generate Get and Set as explains in http://blog.idleman.fr/snippet-24-php-allegez-vos-classes-avec-des-getset-automatiques/
	 * @param  string $m
	 * @param  string $p
	 * @return string
	 */
	function __call($m,$p){
		$v = strtolower(substr($m,3));
		if (!strncasecmp($m,'get',3))return $this->$v;
		if (!strncasecmp($m,'set',3)) $this->$v = $p[0];
	}

 	/**
 	 * __destruct Close the SQLite database connection
 	 */
 	function __destruct(){
 		$this->close();
 	}

 	function data2object($data,$object){
 		foreach($data as $key => $field){	
 			$setter = "set".$key;
 			$object->$setter($field);
 		}
 		return $object;
 	}

	/**
	 * Convert a type of variable for SQLite3
	 * @param  string $type
	 * @return string Type of variable
	 */
	function sgbdType($type){
		$return = false;
		switch($type){
			case 'string':
			case 'timestamp':
			case 'date':
			$return = 'VARCHAR(255)';
			break;
			case 'longstring':
			$return = 'longtext';
			break;
			case 'key':
			$return = 'INTEGER NOT NULL PRIMARY KEY';
			break;
			case 'object':
			case 'int':
			$return = 'bigint(20)';
			break;
			case 'boolean':
			$return = 'INT(1)';
			break;
			default;
			$return = 'TEXT';
			break;
		}
		return $return ;
	}
	
// SQL MANAGEMENT

	/**
 * Create an entity
 * @author Valentin CARRUESCO
 * @category SQL Management
 * @param <String> $debug='false' active le debug mode (0 ou 1)
 * @return Aucun retour
 */
/**
 * [create description]
 * @category SQL Management
 * @param  string $debug='false' activated debug mode (0 or 1) (DEPRECATED)
 * 
 */
public function create($debug='false'){

	$query = 'CREATE TABLE IF NOT EXISTS `'.SQL_PREFIX.$this->TABLE_NAME.'` (';

		
//Get all fields and generate
		$last = count($this->object_fields);
		$i = 0;
		foreach($this->object_fields as $field=>$type){
			$i++;
			$query .='`'.$field.'`  '. $this->sgbdType($type).'  NOT NULL';
			if($last != $i){$query .=",";}
		}

		$query .= ');';
//Debug Mode
/*
if($this->debug){
	debug("SQL","Create",$this->TABLE_NAME);
	echo $this->TABLE_NAME.' ('.__METHOD__ .') : Requete --> '.$query;
}
*/
if(!$this->exec($query)) echo $this->lastErrorMsg();
}

public function drop($debug='false'){
	$query = 'DROP TABLE IF EXISTS`'.SQL_PREFIX.$this->TABLE_NAME.'`;';
	
	if($this->debug){
		debug("SQL","Drop",$this->TABLE_NAME);
		echo $this->TABLE_NAME.' ('.__METHOD__ .') : Requete --> '.$query;
	}

	if(!$this->exec($query)) echo $this->lastErrorMsg();
}


public function massiveInsert($events){
	$query = 'INSERT INTO `'.SQL_PREFIX.$this->TABLE_NAME.'`(';
		$i=false;
		foreach($this->object_fields as $field=>$type){
			if($type!='key'){
				if($i){$query .=',';}else{$i=true;}
				$query .='`'.$field.'`';
			}
		}
		$query .=') select';
$u = false;
foreach($events as $event){
	if($u){$query .=' union select ';}else{$u=true;}

	$i=false;
	foreach($event->object_fields as $field=>$type){
		if($type!='key'){
			if($i){$query .=',';}else{$i=true;}
			$query .='"'.eval('return htmlentities($event->'.$field.');').'"';
		}
	}

}

$query .=';';
		//echo '<i>'.$this->CLASS_NAME.' ('.__METHOD__ .') : Requete --> '.$query.'<br>';
if(!$this->exec($query)) echo $this->lastErrorMsg().'</i>';

}

	/**
 * Insert or modify entity elements
 * @author Valentin CARRUESCO
 * @category manipulation SQL
 * @param  Aucun
 * @return Aucun retour
 */
	public function save(){

		if(isset($this->id)){
			$query = 'UPDATE `'.SQL_PREFIX.$this->TABLE_NAME.'`';
			$query .= ' SET ';

			$i = false;
			foreach($this->object_fields as $field=>$type){
				if($i){$query .=',';}else{$i=true;}
				$id = eval('return htmlentities($this->'.$field.');');
				$query .= '`'.$field.'`="'.$id.'"';
			}

			$query .= ' WHERE `id`="'.$this->id.'";';
		}else{
			$query = 'INSERT INTO `'.SQL_PREFIX.$this->TABLE_NAME.'`(';

				$this->setUid(uniqid());
				$i=false;
				foreach($this->object_fields as $field=>$type){
					if($type!='key'){
						if($i){$query .=',';}else{$i=true;}
						$query .='`'.$field.'`';
					}
				}
				$query .=')VALUES(';
				$i=false;
				foreach($this->object_fields as $field=>$type){
					if($type!='key'){
						if($i){$query .=',';}else{$i=true;}
						$query .='"'.eval('return htmlentities($this->'.$field.');').'"';
					}
				}

				$query .=');';
}
if($this->debug){
	debug("SQL","Save",$this->TABLE_NAME);
	echo $this->TABLE_NAME.' ('.__METHOD__ .') : Requete --> '.$query;
}

if(!$this->exec($query)) echo $this->lastErrorMsg().'</i>';
$this->id =  (!isset($this->id)?$this->lastInsertRowID():$this->id);
}

	/**
 * M�thode de modification d'�l�ments de l'entit�
 * @author Valentin CARRUESCO
 * @category manipulation SQL
 * @param <Array> $colonnes=>$valeurs
 * @param <Array> $colonnes (WHERE) =>$valeurs (WHERE)
 * @param <String> $operation="=" definis le type d'operateur pour la requete select
 * @param <String> $debug='false' active le debug mode (0 ou 1)
 * @return Aucun retour
 */
	public function change($columns,$columns2=null,$operation='=',$debug='false'){
		$query = 'UPDATE `'.SQL_PREFIX.$this->TABLE_NAME.'` SET ';
		$i=false;
		foreach ($columns as $column=>$value){
			if($i){$query .=',';}else{$i=true;}
			$query .= '`'.$column.'`="'.$value.'" ';
		}

		if($columns2!=null){
			$query .=' WHERE '; 
			$i=false;
			foreach ($columns2 as $column=>$value){
				if($i){$query .='AND ';}else{$i=true;}
				$query .= '`'.$column.'`'.$operation.'"'.$value.'" ';
			}
		}

		if($this->debug)echo '<h1>SQL SAVE CONFIGURATION</h1><i>'.$this->TABLE_NAME.' ('.__METHOD__ .') : Requete --> '.$query.'<br>';
		if(!$this->exec($query)) echo $this->lastErrorMsg();
	}

	/**
 * Display a table inside an Entity objects
 * @author Valentin CARRUESCO
 * @category manipulation SQL
 * @param <String> $ordre=null
 * @param <String> $limite=null
 * @param <String> $debug='false' active le debug mode (0 ou 1)
 * @return <Array<Entity>> $Entity
 */
	public function populate($order='null',$limit='null',$debug='false'){
		eval('$results = $this->loadAll(array(),\''.$order.'\','.$limit.',\'=\','.$debug.');');
		return $results;
	}


	/**
 * M�thode de selection multiple d'elements de l'entit�
 * @author Valentin CARRUESCO
 * @category manipulation SQL
 * @param <Array> $colonnes (WHERE)
 * @param <Array> $valeurs (WHERE)
 * @param <String> $ordre=null
 * @param <String> $limite=null
 * @param <String> $operation="=" definis le type d'operateur pour la requete select
 * @param <String> $debug='false' active le debug mode (0 ou 1)
 * @return <Array<Entity>> $Entity
 */
	public function loadAll($columns,$order=null,$limit=null,$operation="=",$debug='false',$selColumn='*'){
		$objects = array();
		$whereClause = '';

		if($columns!=null && sizeof($columns)!=0){
			$whereClause .= ' WHERE ';
			$i = false;
			foreach($columns as $column=>$value){

				if($i){$whereClause .=' AND ';}else{$i=true;}
				$whereClause .= '`'.$column.'`'.$operation.'"'.$value.'"';
			}
		}
		$query = 'SELECT '.$selColumn.' FROM `'.SQL_PREFIX.$this->TABLE_NAME.'` '.$whereClause.' ';
		if($order!=null) $query .='ORDER BY '.$order.' ';
		if($limit!=null) $query .='LIMIT '.$limit.' ';
		$query .=';';

		//echo '<hr>'.__METHOD__.' : Requete --> '.$query.'<br>';
		$execQuery = $this->query($query);

		if(!$execQuery)
			echo $this->lastErrorMsg();
		$array = false;
		while($queryReturn = $execQuery->fetchArray(SQLITE3_ASSOC) ){
			$array[] = $queryReturn; 
			/*
			$object = eval(' return new Entity($this->TABLE_NAME,$this->user);');
			foreach($this->object_fields as $field=>$type){
				if(isset($queryReturn[$field])) eval('$this->'.$field .'= html_entity_decode(\''. addslashes($queryReturn[$field]).'\');');
			}
			$objects[] = $object;
			unset($object);
			*/
		}

		return $array;
	}

	public function loadAllOnlyColumn($selColumn,$columns,$order=null,$limit=null,$operation="=",$debug='false'){
		eval('$objects = $this->loadAll($columns,\''.$order.'\',\''.$limit.'\',\''.$operation.'\',\''.$debug.'\',\''.$selColumn.'\');');
		if(count($objects)==0)$objects = array();
		return $objects;
	}

	/**
 * M�thode de selection unique d'�lements de l'entit�
 * @author Valentin CARRUESCO
 * @category manipulation SQL
 * @param <Array> $colonnes (WHERE)
 * @param <Array> $valeurs (WHERE)
 * @param <String> $operation="=" definis le type d'operateur pour la requete select
 * @param <String> $debug='false' active le debug mode (0 ou 1)
 * @return <Entity> $Entity ou false si aucun objet n'est trouv� en base
 */
	public function load($columns,$operation='=',$debug='false'){
		eval('$objects = $this->loadAll($columns,null,\'1\',\''.$operation.'\',\''.$debug.'\');');
		if(!isset($objects[0]))$objects[0] = false;
		return $objects[0];
	}

	/**
 * M�thode de selection unique d'�lements de l'entit�
 * @author Valentin CARRUESCO
 * @category manipulation SQL
 * @param <Array> $colonnes (WHERE)
 * @param <Array> $valeurs (WHERE)
 * @param <String> $operation="=" definis le type d'operateur pour la requete select
 * @param <String> $debug='false' active le debug mode (0 ou 1)
 * @return <Entity> $Entity ou false si aucun objet n'est trouv� en base
 */
	public function getById($id,$operation='=',$debug='false'){
		return $this->load(array('id'=>$id),$operation,$debug);
	}

	/**
 * Methode de comptage des �l�ments de l'entit�
 * @author Valentin CARRUESCO
 * @category manipulation SQL
 * @param <String> $debug='false' active le debug mode (0 ou 1)
 * @return<Integer> nombre de ligne dans l'entit�'
 */
	public function rowCount($columns=null)
	{
		$whereClause ='';
		if($columns!=null){
			$whereClause = ' WHERE ';
			$i=false;
			foreach($columns as $column=>$value){
				if($i){$whereClause .=' AND ';}else{$i=true;}
				$whereClause .= '`'.$column.'`="'.$value.'"';
			}
		}
		$query = 'SELECT COUNT(id) FROM '.SQL_PREFIX."`".$this->TABLE_NAME."`".$whereClause;
		//echo '<hr>'.$this->TABLE_NAME.' ('.__METHOD__ .') : Requete --> '.$query.'<br>';
		$execQuery = $this->querySingle($query);
		//echo $this->lastErrorMsg();
		return (!$execQuery?0:$execQuery);
	}	
	
	/**
 * M�thode de supression d'elements de l'entit�
 * @author Valentin CARRUESCO
 * @category manipulation SQL
 * @param <Array> $colonnes (WHERE)
 * @param <Array> $valeurs (WHERE)
 * @param <String> $operation="=" definis le type d'operateur pour la requete select
 * @param <String> $debug='false' active le debug mode (0 ou 1)
 * @return Aucun retour
 */
	public function delete($columns,$operation='=',$debug='false',$limit=null){
		$whereClause = '';

		$i=false;
		foreach($columns as $column=>$value){
			if($i){$whereClause .=' AND ';}else{$i=true;}
			$whereClause .= '`'.$column.'`'.$operation.'"'.$value.'"';
		}
		$query = 'DELETE FROM `'.SQL_PREFIX.$this->TABLE_NAME.'` WHERE '.$whereClause.' '.(isset($limit)?'LIMIT '.$limit:'').';';
		if($this->debug)echo '<h1>SQL DELETE TABLE</h1><hr>'.$this->TABLE_NAME.' ('.__METHOD__ .') : Requete --> '.$query;
		if(!$this->exec($query)) echo $this->lastErrorMsg();
	}
	
	public function customExecute($request){
		$this->exec($request);
	}
	public function customQuery($request){
		return $this->query($request);
	}

	// ACCESSEURS
		/**
 * M�thode de r�cuperation de l'attribut debug de l'entit�
 * @author Valentin CARRUESCO
 * @category Accesseur
 * @param Aucun
 * @return <Attribute> debug
 */

		public function getDebug(){
			return $this->debug;
		}

	/**
 * M�thode de d�finition de l'attribut debug de l'entit�
 * @author Valentin CARRUESCO
 * @category Accesseur
 * @param <boolean> $debug 
 */

	public function setDebug($debug){
		$this->debug = $debug;
	}

	public function getObject_fields(){
		return $this->object_fields;
	}

	public function setId($id){
		$this->id = $id;
		$this->setUid(uniqid());
	}

	public function setCustomTable($table_name,$db_fields){
		$db_fields['id'] = "key";
		$db_fields['uid'] = "int";
		$db_fields['object_name'] = "string";
		$db_fields['object_description'] = "string";
		$this->object_fields = $db_fields;

		$this->TABLE_NAME = $table_name;
	}

	public function setTable($table_name,$fields_filepath){
		$db_text = file($fields_filepath);
		array_shift($db_text); //Remove first line (description of schema)

		$db_fields['id'] = "key";
		$db_fields['uid'] = "int";

		if($this->object_table){
			$db_fields['object_name'] = "string";
			$db_fields['object_description'] = "string";
		}

		foreach($db_text as $field){
			$field_info = explode(":",$field);
			$db_fields[$field_info[0]] = $field_info[1];
		}
		$this->object_fields = $db_fields;
		$this->TABLE_NAME = $table_name;
	}


}
?>
