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
            if (isset($profile->gender))
                $gender = $profile->gender == 'male' ? 2 : 1;
            else
                $gender = 0;
            array_push($data,[
                'fb_id'         => $profile->id,
                'firstname'     => $profile->first_name,
                'lastname'      => $profile->last_name,
                'locale'        => $profile->locale,
                'gender'        => $gender,
                'name'          => $profile->name,
                'people_id'     => $profile->user_id,
                'fb_updated'    => $profile->updated_time,
                'zipcode'       => $importedData[0]->cp,
                'birth'         => date("y/m/d", strtotime($importedData[0]->born)),
                'country'       => $importedData[0]->country,
                'lat'           => $importedData[0]->lat,
                'lgt'           => $importedData[0]->lgt,

            ]);
        }
        $this->saveMany($data);
    }
}