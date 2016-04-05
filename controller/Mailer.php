<?php

class Mailer extends Controller
{
    public function prepareShooting()
    {
        $this->loadModel('Shooter');
        $shooter = $this->Shooter->getShooters();
        print_r($shooter);
    }
}