<?php

namespace Model;

class ClidData extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    private $tableName = 'CLID_DATA';

    /**
     * Getting all data from CLID_DATA table.
     */
	public function getData(){
		$data = $this->db->query('SELECT * FROM ' . $this->tableName )->fetchAll();
		return $data;
	}
}
