<?php

class form
{
    //Get Form name, initialize array
    function __construct($name = false)
    {
        $this->input_array = [];
        $this->help_array = [];
        $this->form_name = $name;
    
    }

//Add an error message box with a list of translatables text
    function help($text)
    {
        $this->help_array[] = [
        "text" => t($text)
        ];
    }

//Add an input into the form
    function input($input)
    {
        $this->input_array[] = $input;
    }

//Display a form
    function display($tpl, $buttons = true)
    {
        //Get data
        $tpl->assign("form_name", $this->form_name);
        $tpl->assign("help_array", $this->help_array);
        
        //Form Header
        $tpl->draw(CORE_TEMPLATES."forms/form_header");

        //Form inputs
        $inputs_path = CORE_TEMPLATES."forms/inputs/";
        foreach ($this->input_array as $input) {
            $tpl->assign("input", $input);
            $tpl->draw($inputs_path.$input["type"]);
        }

        //Form Footer
        if ($buttons) {
            $tpl->draw(CORE_TEMPLATES."forms/form_footer");
        } else {
            $tpl->draw(CORE_TEMPLATES."forms/form_footer_nobuttons");
        }
    }
}
