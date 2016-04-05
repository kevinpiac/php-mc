<?php

class Mailers extends Controller
{
    public function prepareShooting()
    {
        $this->loadModel('Shooter');
        $this->loadModel('People');
        print_r($this->People);
        print_r($this->Shooter);

    }
}