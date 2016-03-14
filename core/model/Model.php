<?php

class Model
{
    static $connections = array();

    public $db = 'test';

    public function __construct()
    {
        $conf = Database::$databases[$this->db];
        if (isset(Model::$connections[$this->db]))
            return (true);
        try
        {
            $pdo = new PDO('mysql:host='.$conf['host'].';dbname='.$conf['db_name'], $conf['user'], $conf['password']);
            Model::$connections[$this->db] = $pdo;
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
        }
    echo "Connexion a la base";
    }
}
