<?php

class Shooter extends Model
{
    public $table = 'Shooter';
    
    private $max_month_mail = 10000;
    private $max_hour_mail = 200;

    public function getShooters()
    {
        $shooters = $this->find(array(
            'fields' => array(
                'Shooter.id',
                'Shooter.email',
            ),
            'join' => array(
                'table' => 'Domain',
                'model' => 'Domain',
                'on' => array('Shooter.domain_id = Domain.id')
            ),
            'conditions' => array(
                'Shooter.month_mail_count <= 10000', // un mail envoie 10 000 mails / mois
                'Shooter.hour_mail_count <= 200', // un mail envoie 200 mails / heure
                'Domain.hour_mail_count <= 150' // un domaine (une ip) envoie 150 mail / heure
            )
        ));
        return ($shooters);
    }
}