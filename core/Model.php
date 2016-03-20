<?php

class Model
{
    static $connections = array();

    public $conf = 'vpsKevin';
    public $db;
    public $table = false;
    public $debug = true;

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
        if (!empty($params['fields']) || !empty($params['join']['fields']))
        {
            if (!empty($params['fields']))
            {
                $fields = $params['fields'];
                foreach ($fields as $k => $v)
                    $fields[$k] = $v;
                $fields = implode(", ",$fields);
            }

            if (!empty($params['join']['fields']))
            {
                $joinFields = $params['join']['fields'];
                foreach ($joinFields as $k => $v)
                {
                    $joinFields[$k] = $v;
                }
                $joinFields = implode(", ", $joinFields);
                $joinFields = ", ".$joinFields;
                $fields .= $joinFields;
            }
        }
        else
            $fields = '*';
        $req = 'SELECT ' .$fields. ' FROM '.$this->table. ' as '. get_class($this) . '';
        if (!empty($params['join']))
        {
            $req .= ' INNER JOIN '. $params['join']['table']. ' as '. $params['join']['model'].' ON ';
            foreach ($params['join']['on'] as $k => $v)
            {
                if (!is_numeric($k))
                    $req .= $k.' '.'\''.$v.'\'';
                else
                    $req .= $v;
            }
        }
        if (!empty($params['conditions']))
        {
            $req .= ' WHERE ';
            foreach ($params['conditions'] as $k => $v)
            {
                if (!is_numeric($k))
                    $req .= $k. ' ' .'\''.$v.'\''. ' AND ';
                else
                    $req .= $v. ' AND ';                    
            }
            $req = substr($req, 0, -4);
        }
        if (!empty($params['order']))
        {
            $req .= ' ORDER BY ';
            foreach ($params['order'] as $k => $v)
            {
                $req .= $k. ' '. strtoupper($v). ', ';
            }
            $req = substr($req, 0, -2);
        }
        if (!empty($params['limit']))
            $req .= ' LIMIT '.$params['limit'];
        if ($this->debug == true)
        {
            print_r("START OF DEBUG MODE FOR MODEL\n------------\n\n");
            print_r($req);
            print_r("\n------------\nEND OF DEBUG MODE FOR MODEL\n\n");
        }
        $pre = $this->db->prepare($req);
        $pre->execute();
        return ($pre->fetchAll(PDO::FETCH_OBJ));
    }

    public function findFirst($params)
    {
        $res = current($this->find($params));
        if (isset($res->id))
            $this->id = $res->id;
        return ($res);
    }

    public function findByQuery($query)
    {
        if ($this->debug == true)
        {
            print_r("START OF DEBUG MODE FOR MODEL\n------------\n\n");
            print_r($req);
            print_r("\n------------\nEND OF DEBUG MODE FOR MODEL\n\n");
        }
        $pre = $this->db->prepare($query);
        $pre->execute();
        return ($pre->fetchAll(PDO::FETCH_OBJ));
    }

    public function findById($id, $fields = null)
    {
        if (!empty($fields))
        {
            $res = $this->findFirst(array(
                'conditions' => array(
                    'id =' => $id
                ),
                'fields' => $fields
            ));
        }
        else
        {
            $res = $this->findFirst(array(
                'conditions' => array(
                    'id =' => $id
                )
            ));
        }
        $this->id = $id;
        return ($res);
    }

    public function save($data)
    {
        if (!isset($this->id))
        {
            $req = 'INSERT INTO ' .$this->table.'(';
            foreach ($data as $f => $v)
            {
                $req .= $f. ', ';
            }
            $req = substr($req, 0, -2);
            $req .= ') VALUES(';
            foreach ($data as $f => $v)
            {
                $req .= ':'.$f. ', ';
            }
            $req = substr($req, 0, -2);
            $req .= ')';
            if ($this->debug == true)
                print_r($req);
            $pre = $this->db->prepare($req);
            $pre->execute($data);
        }
        else
            $this->updateById($this->id, $data);
    }

    public function create()
    {
        if (isset($this->id))
            $this->id = null;
    }
    
    public function update($params)
    {
        $req = 'UPDATE '.$this->table.' SET ';
        foreach ($params['fields'] as $f => $v)
        {
            $req .= $f.' = :'.$f.', ';
        }
        $req = substr($req, 0, -2);
        $req .= ' WHERE ';
        foreach ($params['conditions'] as $k => $v)
        {
            if (!is_numeric($k))
                $req .= $k. ' ' .'\''.$v.'\''. ' AND ';
            else
                $req .= $v. ' AND ';                    
        }
        $req = substr($req, 0, -5);
        if ($this->debug == true)
            print_r($req);
        $pre = $this->db->prepare($req);
        $pre->execute($params['fields']);
    }

    public function updateById($id, $fields)
    {
        $cond = array(
            'conditions' => array(
                'id =' => $id
            )
        );
        $fields = array(
            'fields' => $fields
        );
        $params = array_merge($cond, $fields);
        $this->update($params);
    }
}