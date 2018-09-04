<?php

namespace Model;

class ScpfData extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    private $tableName = 'SCPF_DATA';

	public function getOpenDates($forma)
	{
		$data = $this->db->query("SELECT s.DATEOPEN FROM SCPF_DATA s left join CLID_DATA c ON s.ID_CLID = c.ID_CLID where c.FORMA ='$forma' GROUP BY s.DATEOPEN")->fetchAll(\PDO::FETCH_COLUMN);
		return $data;
	}

	public	function getCloseDates($forma)
	{
		$data = $this->db->query("SELECT s.DATECLOSE FROM SCPF_DATA s left join CLID_DATA c ON s.ID_CLID = c.ID_CLID where c.FORMA ='$forma' GROUP BY s.DATECLOSE")->fetchAll(\PDO::FETCH_COLUMN);
		return $data;
	}

	public	function getOpenCount($date, $forma, $source)
	{
		$data = $this->db->query("SELECT COUNT(*) FROM SCPF_DATA s LEFT JOIN CLID_DATA c ON s.ID_CLID = c.ID_CLID WHERE DATEOPEN='$date'" . " AND c.FORMA='$forma' AND s.SOURCE_OPEN ='$source'")->fetchColumn();
		return $data;
	}

	public	function getCloseCount($date, $forma)
	{
		$data = $this->db->query("SELECT COUNT(*) FROM SCPF_DATA s LEFT JOIN CLID_DATA c ON s.ID_CLID = c.ID_CLID WHERE DATECLOSE='$date'" . " AND c.FORMA='$forma'")->fetchColumn();
		return $data;
	}

	public	function getJurData()
	{
		$data = $this->db->query('SELECT * FROM ' . $this->tableName . ' s left join CLID_DATA c ON s.ID_CLID = c.ID_CLID where c.FORMA ="JUR" ')->fetchAll();
		return $data;
	}

	public	function getFlpData()
	{
		$data = $this->db->query('SELECT * FROM ' . $this->tableName . ' s left join CLID_DATA c ON s.ID_CLID = c.ID_CLID where c.FORMA ="FLP" ')->fetchAll();
		return $data;
	}

	public	function getApiData($sql)
	{
		$data = $this->db->query("SELECT FORMA, ACC_NUMBER, s.ID_CLID as ID_CLID, DATEOPEN, DATECLOSE, SOURCE_OPEN FROM SCPF_DATA s left join CLID_DATA c ON s.ID_CLID = c.ID_CLID where 1=1 $sql")->fetchAll(\PDO::FETCH_ASSOC);
		return $data;
	}

	/*
	 *
	 */
	public	function getCountData($forma, $dates, $source)
	{
		$open  = [];
		$close = [];
		foreach ( $dates as $date )
		{
			$open[]  = intval(self::getOpenCount($date, $forma, $source));
			$close[] = intval(self::getCloseCount($date, $forma)) * -1;
		}

		$data['open']  = $open;
		$data['close'] = $close;

		return $data;
	}

	/*
  	* Get dates for building graphs
 	*/
	public function getDataDates($type)
	{
		$openDates  = self::getOpenDates($type);
		$closeDates = self::getCloseDates($type);

		$mergeArray = array_merge($openDates, $closeDates);
		$dates      = array_unique($mergeArray);

		foreach ( $dates as $key => $date )
		{
			if ( $date == NULL )
			{
				unset($dates[$key]);
			}
		}

		sort($dates);

		return $dates;

	}

	/*
	 * preparing API data
	 */
	public	function prepareApiData($post)
	{
		$sql = '';
		if ( !empty($post['FORMA']) )
		{
			$forma = $post['FORMA'];
			$sql   = $sql . " AND c.FORMA ='$forma'";
		}

		if ( !empty($post['ID_CLID']) )
		{
			$id  = $post['ID_CLID'];
			$sql = $sql . " AND s.ID_CLID ='$id'";
		}

		if ( !empty($post['SOURCE_OPEN']) )
		{
			$source = $post['SOURCE_OPEN'];
			$sql    = $sql . " AND s.SOURCE_OPEN  ='$source'";
		}

		$data['ACC'] = self::getApiData($sql);

		return $data;
	}
}
