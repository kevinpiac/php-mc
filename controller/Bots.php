<?php

class Bots extends Controller
{
    public function sayHello($params)
    {
        if (!empty($params))
        {
            echo "Hello! Params are : ";
            foreach ($params as $param)
                echo $param. " ";
        }
        else
        {
            echo "Are you kiding me bro ? You passed no param...";
        }
    }

    public function activeToken()
    {
        $this->loadModel('Card');
        $this->Card->findByQuery("SELECT * FROM Card");
    }
    
}