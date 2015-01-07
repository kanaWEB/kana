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
		foreach($file_array as $key => $line){
			if($isblock){
				$tr = 0;
				if(trim($line) != ""){
					$line_array = explode("|",$line);

					$blocks[$nb] = [
					"name" => trim($line_array[0]),
					"icon" => trim($line_array[1])
					];
					$isblock = false;
				}
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
						$user_file = USER_VIEWS.$plugin."/data/".$data_file.".view";
						$core_file = CORE_VIEWS.$plugin."/".$data_file.".view";

						if(file_exists($user_file)){
							include($user_file);
						}
						elseif(file_exists($core_file)){
							$blocks[$nb]["row"][$tr]["custom"] = [
							"link" => $core_file
							];
						//include($core_file);
						}
						else{
							echo "I can't find ".$user_file." or ".$core_file." !";
							exit();
						}

						$tr++;
					}
				}
				else{
					$isdata = true;
				}
			}

		}
		if(DEBUG){Functions::pretty_debug($blocks);}
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

	//Generate widgets of objects
	public static function objects_widgets($current_group,$tpl){
		$webobjects = Variable::objects_to_webobjects($current_group);
		//var_dump($webobject);
		if(isset($webobjects)){
			$col = 0;

			foreach($webobjects as $key => $webobject){


				$webobject["widget_id"] = $key;
				$tpl->assign("webobject",$webobject);
				$widget_override_path = USER_OBJECTS.$webobject["type"]."/widgets/".$webobject["widget"] ."/".$webobject["widget"];
				//var_dump($widget_override_path);
				$widget_default = CORE_WIDGETS."switch/switch";

				//If widget is not the default one, override widgets 
				if(file_exists($widget_override_path.".html")){
					$widget = $widget_override_path;
				}
				else{
					$widget = $widget_default;
				}


				if($col == 0){
					?>
					<div class="col-sm-3">
					<?
				}
				$tpl->draw($widget);
				$col++;

				if($col == 2){
					$col = 0;
					?>
					</div>
					<?
				}	

				

			}
			$tpl->draw(CORE_VIEWS."grids/block/close");
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function sensors_widgets($current_group,$tpl){
		$websensors = Variable::sensors_to_websensors($current_group);
		//var_dump($websensors);
		if(isset($websensors)){
			foreach($websensors as $websensor){
				$tpl->assign("websensor",$websensor);
				$widget = USER_SENSORS.$websensor["sensor_type"]."/widgets/".$websensor["sensor_type"]."/".$websensor["sensor_type"];
				$tpl->draw($widget);
			}
			return true;
		}
		else{
			return false;
		}
	}

	public static function menu_allobjects($tpl){
		$tpl->assign("text","There is nothing in this group, do you want to create an object?");
		$tpl->draw(CORE_VIEWS."text/h1");
		$tpl->assign("settingsmenu_active",false);
		include(CORE_VIEWS."menu/settings/objects/objects.view");
	}
}
?>