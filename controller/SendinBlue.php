<?php

use Sendinblue\Mailin ;
        
class SendinBlue extends Controller
{
    
    public function GetTemplate()
    {
        $mailin = new Mailin('https://api.sendinblue.com/v2.0','ph1Tx72LKAHfmV6E');

        $campaigns = $mailin->get_campaign_v2([
            'id' => 151
        ]);        
        
        $a = $campaigns['data'][0]['html_content'];
        $a = str_replace('{LINK}', "MON LIEN", $a);
        print_r($a);
    }
}