<?php

class Shooter extends Model
{
    public $table = 'Shooter';
    
    public function getShooters()
    {
        $shooters = $this->find(array(
            'fields' => array(
                'Shooter.id',
                'Shooter.email',
                'Proxy.ip'
            ),
            'joins' => array(
                array(
                    'table' => 'Domain',
                    'model' => 'Domain',
                    'on' => array('Shooter.domain_id = Domain.id')
                ),
                array(
                    'table' => 'Proxy',
                    'model' => 'Proxy',
                    'on' => array('Proxy.domain_id = Domain.id')
                )
            ),
            'conditions' => array(
                'Shooter.month_mail_count <= 10000', // un mail envoie 10 000 mails / mois
                'Shooter.hour_mail_count <= 200', // un mail envoie 200 mails / heure
                'Domain.hour_mail_count <= 150', // un domaine (une ip) envoie 150 mail / heure
                'Proxy.down != 1', // le proxy ne doit pas etre down
                'Proxy.expiry > DATE(NOW())' // le proxy ne doit pas avoir expire
            ),
            'limit' => 10 // voir avec thomas
        ));
        return ($shooters);
    }
}