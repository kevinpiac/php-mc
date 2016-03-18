<?php

class ShellRouter {

    public $url;
    public $controller;
    public $action;
    public $params = array();
    
    public function __construct($ac, $av)
    {
        $this->controller = $av[1];
        if (empty($av[2]))
            $this->action = 'index';
        else
            $this->action = $av[2];
        for ($i = 3 ; $i < $ac ; $i++)
        {
            array_push($this->params, $av[$i]);
        }
    }
}

