<?php

class People extends Model
{
    public $table = 'People';

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