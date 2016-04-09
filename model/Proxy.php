<?php

class Proxy extends Model
{
    public $table = 'Proxy';

     public function setProxyDownByIp($proxy)
    {
        $query = "UPDATE Proxy SET down = 1 WHERE ip = ";
        $query .= "'".$proxy. "'" ;
        
        $this->query($query);
    }
}
