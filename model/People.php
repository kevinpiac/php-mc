<?php

class People extends Model
{
    public $table = 'People';

    public function saveManyPeople($profiles)
    {
        $data = [];
        foreach ($profiles as $profile)
        {
            array_push($data,[
                'id'            => $profile->user_id,
                'email'         => $profile->email,
                'unsubscribe'   => 0,
                // ajouter les autres champs a sauvegarder ici.
            ]);
        }
        $this->saveMany($data);
    }

    // fonction qui save many pour chaque jointures;
    public function saveAllPeople($data)
    {
        require (ROOT.DS.'model'.DS.'PeopleProfile.php'); // create joins in config to avoid that !
        require (ROOT.DS.'model'.DS.'PeopleActivity.php'); // create joins in config to avoid that !
        
        $this->PeopleProfile = new PeopleProfile; 
        $this->PeopleActivity = new PeopleActivity;

        $this->saveManyPeople($data);
        $this->PeopleProfile->saveManyProfiles($data);
    }

    public function getToShoot()
    {
        $people = $this->find([
            'fields' => [
                'People.id',
                'People.email',
                'PeopleProfile.firstname',
                'PeopleProfile.lastname',
            ],
            'joins' => [
                [
                    'table' => 'PeopleProfile',
                    'model' => 'PeopleProfile',
                    'on' => ['PeopleProfile.people_id = People.id']
                ],
                [
                    'table' => 'PeopleActivity',
                    'model' => 'PeopleActivity',
                    'on' => ['PeopleActivity.people_id = People.id']
                ],
            ],
            'conditions' => [
                'People.unsubscribe = 0',
                'PeopleActivity.status > 0',
            ],
            'order' => [
                'People.id' => 'desc' // FOR TEST ONLY
            ],
            'limit' => 5 // FOR TEST ONLY
        ]);
        return ($people);
    }

    
}