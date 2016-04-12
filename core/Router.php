<?php

class Router {

    public $url;
    public $controller;
    public $action;
    public $params = [];
    public $debug = false;
    

/****************************************
/////////////// TO DO: CREATE A GLOBAL ROUTER FOR ALL REQUEST
*****************************************************/

    public function loadController($name)
    {
        $file = ROOT.DS.'controller'.DS.ucfirst($name).'.php';
        require_once($file);
        if (!isset($this->Controller))
        {
            return ($this->Controller = new $name());
        }
    }
    ////////////// PASSER LOAD CONTROLLER EN STATIC
    public function __construct($url)
    {
        $explod = explode('/', $url);
        $this->controller = $explod[0];
        $this->action = (empty($explod[1])) ? 'index' : $explod[1];
        $i = 0;
        if (isset($explod[2]))
        {
            foreach ($explod as $k => $v)
            {
                if ($i > 1)
                {
                    array_push($this->params, $v);
                }
                $i++;
            }
        }
        return ($this->loadController($this->controller));
    }

    public function executeHttpAction()
    {
        $action = $this->action;
        $this->Controller->$action($this->params);
    }

    public static function getUrl($url)
    {
        
    }
}

