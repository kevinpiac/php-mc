<?php

class Cards extends Controller
{
    public $name = 'cards';

    public function index()
    {

    }
    
    public function sayHello($params)
    {
        echo ("Les paramertres sont : ". $params[0]. " et ".$params[1]);
    }
}