<?php

class People extends Model
{
    public $table = 'People';
    public $debug = true;

    public function getToShoot()
    {
        $laters = $this->findByQuery(
                "SELECT profile.id, profile.firstname, profile.lastname
                 FROM PeopleProfile as profile
                 INNER JOIN PeopleActivity as activity
                 ON 
                 activity.people_id = profile.people_id
                 WHERE
                 activity.status = 2
                ORDER BY activity");
        return ($res);
    }
}