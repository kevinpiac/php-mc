<?php

class Controller
{
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
