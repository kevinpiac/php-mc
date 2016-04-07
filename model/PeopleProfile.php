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
            $gender = $profile->gender == 'male' ? 2 : 1;
            array_push($data,[
                'fb_id'         => $profile->id,
                'firstname'     => $profile->first_name,
                'lastname'      => $profile->last_name,
                'locale'        => $profile->locale,
                'gender'        => $gender,
                'name'          => $profile->name,
                'people_id'     => $profile->user_id
            ]);
        }
        $this->saveMany($data);
    }
}