<?php
/* DRAWING FUNCTIONS */

class Draw
{



//Ajax Notification
//If a PHP file is called in ajax, you can make it return a notification
public static function ajax_notify($text,$type,$data = False){
	$message = [
	"text" => t($text),
	"type" => $type,
	"data" => $data
	];
	echo json_encode($message);
	//echo $type." !::! ".t($text);
}




//Move to another functions (CoreDraw::md2datable)
public static function md2datatable($filename,$datadir){
	$file_array = file($filename);
	$isdata = false;
	$isblock = true;
	$nb = 0;
	$tr = 0;
	foreach($file_array as $key => $line){
		if($isblock){
			$line_array = explode("|",$line);
			$blocks[$nb] = [
			"name" => trim($line_array[0]),
			"icon" => trim($line_array[1])
			];
			$isblock = false;
		}
		else{
			if($isdata){
				//@todo choose between views/data
				
				$line_array = explode("|",$line);
				

				$plugin = trim($line_array[0]);
				if($plugin == ""){
					$isdata = false;
					$isblock = true;
					$nb++;
				}
				else{
					$data_file = trim($line_array[1]);
					include(USER_VIEWS.$plugin."/data/".$data_file.".view");
					$tr++;
				}
			}
			else{
				$isdata = true;
			}
		}
		
	}
	return $blocks;

}

//Move to another functions (CoreDraw::md2help)
public static function md2help($object_name){
	$Parsedown = new Parsedown();
	$translated_help = USER_OBJECTS.$object_name."/help/help.".$_SESSION["LANGUAGE"].".md";

	if(file_exists($translated_help)){
		$text = file_get_contents($translated_help);
	}
	else
	{
		$text = file_get_contents(USER_OBJECTS.$object_name."/help/help.md");
	}
	
	echo $Parsedown->text($text);
}

public static function md2available_actions($object_name,$tpl){
	
}
}
?>