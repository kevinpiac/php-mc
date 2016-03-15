<?php

class Model
{
    static $connections = array();

    public $conf = 'test';
    public $db;
    public $table = false;

    public function __construct()
    {
        $conf = Database::$databases[$this->conf];
        if (isset(Model::$connections[$this->conf]))
            return (true);
        try
        {
            $pdo = new PDO('mysql:host='.$conf['host'].';dbname='.$conf['db_name'], $conf['user'], $conf['password']);
            Model::$connections[$this->conf] = $pdo;
            $this->db = $pdo;
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
        }
    }

    public function find($params)
    {
        $req = 'SELECT * FROM '.$this->table. ' as ' .get_class($this). '';
        echo"searching -> ".$req;

        $pre = $this->db->prepare($req);
        $pre->execute();
        return ($pre->fetchAll(PDO::FETCH_OBJ));
    }
}
