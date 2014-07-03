<?php

class Theme{

	private $theme_name,$theme_js,$theme_css;
	function __construct($theme_name=false){
		
		//Get datas needed for a theme
		$template_path = USER_THEME.$theme_name;

		$this->theme_name = $theme_name;

		if(file_exists($template_path."/js/")){
			$js_autoload = scandir($template_path."/js");
			array_shift($js_autoload);
			array_shift($js_autoload);
			foreach($js_autoload as $js){
				$this->theme_js[] = $template_path."/js/".$js;
			}
		}

		if(file_exists($template_path."/css/")){
			$css_autoload = scandir($template_path."/css");
			array_shift($css_autoload);
			array_shift($css_autoload);

			foreach($css_autoload as $css){
				$this->theme_css[] = $template_path."/css/".$css;
			}
		}
	}

	function css(){
		return $this->theme_css;
	}

	function js(){
		return $this->theme_js;
	}

	//Get list of available theme
	function getlist(){
		$theme_file = scandir(USER_THEME);
		array_shift($theme_file);
		array_shift($theme_file);
		foreach($theme_file as $theme)
		$theme_list[] = [
		"value" => $theme,
		"text" => $theme
		];
		return $theme_list;
	}

}

?>