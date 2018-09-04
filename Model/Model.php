<?php

namespace Model;

class Model
{
    /**
     * @var \PDO
     */
    protected $db;

    public function __construct()
    {
        $this->db = new \PDO('mysql:host=localhost;dbname=id6947096_bankdb', 'id6947096_user', '123456');
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}
