<?php

class People extends Model
{
    public $table = 'People';

    public function getToShoot()
    {
        /*
        $laters = $this->findByQuery(
                "SELECT profile.id, profile.firstname, profile.lastname
                 FROM PeopleProfile as profile
                 INNER JOIN PeopleActivity as activity
                 ON 
                 activity.people_id = profile.people_id
                 WHERE
                 activity.status = 2
                ORDER BY activity");
        */
        $laters = $this->find([
            'fields' => [
                'People.id',
                'People.email',
                'PeopleProfile.firstname',
                'PeopleProfile.lastname'
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
                'PeopleActivity.status = 2',
                
            ]
        ]);
        return ($laters);
    }
}