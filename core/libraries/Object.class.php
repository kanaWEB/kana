<?php
class Object{

	function __construct($object,$type="object"){
		if($type == "sensors"){
			$this->dir = USER_SENSORS.$object;
		}
		else{
			$this->dir = USER_OBJECTS.$object;
		}

		//var_dump($this->dir);
		$this->object = $object;
		$this->icon = $this->dir."/icon.png";
		//var_dump($this->icon);

     	//Get json info files dir
		if(is_dir($this->dir)){
		//Check for menu translation
			$translated_menu_dir = $this->dir."/info/info.".$_SESSION["LANGUAGE"].".json";

			if(file_exists($translated_menu_dir)){
				$this->json_dir = $translated_menu_dir;
			}
			else{
		//If no translation check for menu 
				if(file_exists($this->dir."/info/info.json")){
					$this->json_dir = $this->dir."/info/info.json";
				}
			}
		}
	}

	function json(){
		if(isset($this->json_dir)){

			$this->json = file_get_contents($this->json_dir);
			$this->json = json_decode($this->json);
			if($this->json == null){
				var_dump($this->object. " json is misintepreted");
				exit();
			}

			return $this->json;
		}
		else
		{
			var_dump($this->object. " no info.json");
			//exit();
		}
		
	}

//Make a settingsmenu item for objects (icon/text/link/label)
	function menu_item(){
		$settingsmenu_item = [
		"text" => $this->json->name,
		"description" => $this->json->description,
		"icon" => $this->icon,
		"link" => $this->object,
		"label" => "label-danger",
		];
		return $settingsmenu_item;
	}

	function html($object_dir){
		$object_name = self::Name($object_dir);
		$object_name = 'data-content="<img src="plugins/objects/'.$object_dir.'/icon.png">"  '.$object_name;
		$object_html = [
		"text" => $object_name,
		"img" => $object_dir
		];
		return $object_html;
	}

	function Title(){
		$object_html = '<h1><img src='.$this->icon.'> '.$this->json->name.'</h1>';
		return $object_html;
	}

//Markdown to Name of an objects
	public function Name($object_dir){
	//var_dump("objectName Path:".$object_dir);
	//Check if objects directory exists
		if(is_dir($object_dir)){

		}


		if(isset($menu_filepath)){
			$menu_name = file_get_contents($menu_filepath);
			return $menu_name;
		}
		else
		{
			return false;
		}
	}

	public function objectDescription($object_dir){
	//Check if objects directory exists
		if(is_dir($object_dir)){
		//Check for menu translation
			$translated_menu_dir = $object_dir."/info/description.".$_SESSION["LANGUAGE"].".md";

			if(file_exists($translated_menu_dir)){
				$menu_filepath = $translated_menu_dir;
			}
			else{
		//If no translation check for menu 
				if(file_exists($object_dir."/info/description.md")){
					$menu_filepath = $object_dir."/info/description.md";
				}
			}
		}


		if(isset($menu_filepath)){
			$menu_name = file_get_contents($menu_filepath);
			return $menu_name;
		}
		else
		{
			return false;
		}
	}


//Markdown to Name of an objects
	public function Tags($object_dir){
	//Check if objects directory exists
		if(is_dir($object_dir)){
		//Check for menu translation
			$translated_menu_dir = $object_dir."/info/tags.".$_SESSION["LANGUAGE"].".md";

			if(file_exists($translated_menu_dir)){
				$menu_filepath = $translated_menu_dir;
			}
			else{
		//If no translation check for menu 
				if(file_exists($object_dir."/info/tags.md")){
					$menu_filepath = $object_dir."/info/tags.md";
				}
			}
		}


		if(isset($menu_filepath)){
			$menu_name = file_get_contents($menu_filepath);
			return $menu_name;
		}
		else
		{
			return false;
		}
	}


}