<?php

class ShellRouter {

    public $url;
    public $controller;
    public $action;
    public $params = array();
    public $debug = false;
    
    public function loadController($name)
    {
        $file = ROOT.DS.'controller'.DS.$name.'.php';
        require_once($file);
        if (!isset($this->Controller))
        {
            return ($this->Controller = new $name());
        }
    }
    ////////////// PASSER LOAD CONTROLLER EN STATIC
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
        if ($this->debug == true)
        {
            print_r("START OF DEBUG MODE FOR SHELL ROUTER\n------------\n\n");
            print_r("controller   ->".$this->controller."\n");
            print_r("action       ->".$this->action."\n");
            $i = 1;
            foreach ($this->params as $param)
            {
                print_r("param[".$i."]     ->".$param."\n");
                $i++;
            }
            print_r("\n------------\nEND OF DEBUG MODE FOR SHELL ROUTER\n\n");
        }
        return ($this->loadController($this->controller));
    }

    public function executeAction()
    {
        $action = $this->action;
        $this->Controller->$action($this->params);
    }
}

