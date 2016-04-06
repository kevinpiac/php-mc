<?php

class PeopleProfile extends Model
{
    public $table = 'PeopleProfile';

    // get the profile data from facebook and save it after been structured as we need.
    public function saveManyProfiles($profiles)
    {
        $data = [];
        foreach ($profiles as $profile)
        {
            array_push($data,[
                'fb_id'         => $profile->id,
                'firstname'     => $profile->first_name,
                'lastname'      => $profile->last_name,
                'locale'        => $profile->locale,
                'gender'        => $profile->gender,
                'name'          => $profile->name,
                'people_id'     => $profile->people_id
            ]);
        }
        $this->saveMany($data);
    }
}