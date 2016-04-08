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
            $query = "SELECT cp, born, country, lat, lgt FROM ToClean WHERE user_id = ". $profile->user_id;

            $importedData = $this->query($query);
            print_r($importedData); die();
                          
            $gender = $profile->gender == 'male' ? 2 : 1;
            array_push($data,[
                'fb_id'         => $profile->id,
                'firstname'     => $profile->first_name,
                'lastname'      => $profile->last_name,
                'locale'        => $profile->locale,
                'gender'        => $gender,
                'name'          => $profile->name,
                'people_id'     => $profile->user_id,
                'updated_fb'    => strtotime($profile->updated_time)
            ]);
        }
        $this->saveMany($data);
    }
}