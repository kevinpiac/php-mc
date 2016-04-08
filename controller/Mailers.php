<?php

class Mailers extends Controller
{
    public function prepareShooting()
    {
        $this->loadModel('Shooter');
        $this->loadModel('People');
        print_r($this->People);
        print_r($this->Shooter);

        $people = $this->People->getToShoot();
        print_r($people);
        $shooter = $this->Shooter->getShooters();
        print_r($shooter);
    }

    public function test()
    {
        $transport = Swift_SmtpTransport::newInstance('SSL0.OVH.NET', 587)
                   ->setUsername('05@nouveaumessage21.ovh')
                   ->setPassword('tomylyjon')
                   ;

        $mailer = Swift_Mailer::newInstance($transport);
        $message = Swift_Message::newInstance('Cool email')
                 ->setSubject('Your subject')
                 ->setFrom(['kevinpiac@gmail.com' => 'kevin de sym'])
                 ->setTo(array('kevinpiac@gmail.com'))
                 ->setBody('Ceci est le body normal.')
                 ->addPart('<p>Ceci est le body en HTML</p>', 'text/html')
                 ;
        $result = $mailer->send($message);
        print_r($result);
    }
}