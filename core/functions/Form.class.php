<?php
class form{
	function __construct($name=false){
		$this->input_array = [];
		$this->help_array = [];
		$this->form_name = $name;
	}

	function help($text){
		$this->help_array[] = [
		"text" => t($text)
		];
	}	
	
	function input($input){
		$this->input_array[] = $input;
}

	function display($tpl){
		//var_dump($this->input_array);
		$path = CORE_TEMPLATE."forms/";
		$tpl->assign("form_name",$this->form_name);
		$tpl->assign("help_array",$this->help_array);
		$tpl->draw($path."form_header");

		foreach($this->input_array as $input){
			$tpl->assign("input",$input);
			$tpl->draw($path.$input["type"]);
		}

		$tpl->draw($path."form_footer");
	}

}

?>
