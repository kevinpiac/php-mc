<?php

class PeopleActivity extends Model
{
    public $table = 'PeopleActivity';

    // get the profile data from facebook and save it after been structured as we need.
    public function saveManyActivities($profiles)
    {
        $data = [];
        foreach ($profiles as $profile)
        {
            array_push($data,[
                'people_id'     => $profile->user_id,
                'status'        => 6,
            ]);
        }
        $this->saveMany($data);
    }
}