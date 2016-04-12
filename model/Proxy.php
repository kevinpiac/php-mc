<?php

class Proxy extends Model
{
    public $table = 'Proxy';

    public function setProxyDownByIp($proxy)
    {
        $query = "UPDATE Proxy SET down = 1, pause = (pause + 1), downed = NOW() WHERE ip = ";
        $query .= "'".$proxy. "'" ;
        
        $this->query($query);
    }

    public function getDownProxys()
    {
        $proxys = $this->find([
            'conditions' => [
                'down = 1'
            ]
        ]);

        return ($proxys);
    }

    public function removeExpiredProxys()
    {
        $query = "DELETE FROM Proxy WHERE Expiry < CURDATE()";
        $this->query($query);
    }

    public function restartProxys()
    {
        // ajouter le nombre max d'heure pour lequel il faut down.
        $query = "UPDATE Proxy 
                SET down = 0 
                WHERE NOW() >= (downed + INTERVAL pause HOUR)";
        $this->query($query);
    }
   
}
