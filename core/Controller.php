<?php

class Controller
{

    public $name;
    
    public static function loadController($name)
    {
        $file = ROOT.DS.'controller'.DS.$name.'.php';
        require_once($file);
        return (new $name);
    }

    public function __construct()
    {

    }

    public function loadModel($name)
    {
        $file = ROOT.DS.'model'.DS.$name.'.php';
        require_once($file);
        if (!isset($this->$name))
        {
            $this->$name = new $name();
        }
    }
}
