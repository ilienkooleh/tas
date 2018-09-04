<?php

namespace Controller;

use Model\ScpfData;

class Site extends Main
{
	public $formaJur = 'JUR';
	public $formaFlp = 'FLP';

	public $sourceSite = 'SITE';
	public $sourceBranch= 'BRANCH';

    /**
     * Index page.
     */
    public function index()
    {
    	$this->checkAuth();
    }

	private	function checkAuth()
	{
		$login_successful = false;

		// check user & pwd:
		if ( isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) )
		{
			$usr = $_SERVER['PHP_AUTH_USER'];
			$pwd = $_SERVER['PHP_AUTH_PW'];
			if ( $usr == 'admin' && $pwd == '12345' )
			{
				$login_successful = true;
			}
		}
		// no login
		if ( !$login_successful )
		{
			// send 401 headers:
			header('WWW-Authenticate: Basic realm="Secret page"');
			header('HTTP/1.0 401 Unauthorized');
			print "Пожалуйста, авторизируйтесь";
		}
		else
		{
			echo $this->renderPhpFile(BP . '/View/index.phtml');
		}
	}

	/*
	 * Getting data for view
	 */
    public function getData()
    {
    	$model = new ScpfData();

        $jurDates = $model->getDataDates($this->formaJur);
        $jurDataSite = $model->getCountData($this->formaJur, $jurDates, $this->sourceSite);
        $jurDataBranch = $model->getCountData($this->formaJur, $jurDates, $this->sourceBranch);
		$data_for_view['jur'] = $this->prepareData( $jurDates, $jurDataSite['open'], $jurDataBranch['open'], $jurDataBranch['close']);

        $flpDates = $model->getDataDates($this->formaFlp);
        $flpDataSite = $model->getCountData($this->formaFlp, $flpDates, $this->sourceSite);
        $flpDataBranch = $model->getCountData($this->formaFlp, $flpDates, $this->sourceBranch);
		$data_for_view['flp'] = $this->prepareData( $flpDates, $flpDataSite['open'], $flpDataBranch['open'], $flpDataBranch['close']);

        echo json_encode($data_for_view);
    }
	/*
	 * Preparing data for view
	 */
	private	function prepareData($dates, $dataSiteOpen, $dataBranchOpen, $dataBranchClose)
	{
		$data = [];

		$data['dates']      = $dates;
		$data['openSite']   = $dataSiteOpen;
		$data['openBranch'] = $dataBranchOpen;
		$data['close']      = $dataBranchClose;

		return $data;

	}

    /*
     * Getting API data
     */
	public function getApiData()
	{
		$post = json_decode(file_get_contents('php://input'), true);
		if ( !empty($post['FORMA']) || !empty($post['ID_CLID']) || !empty($post['SOURCE_OPEN']) )
		{
			$model = new ScpfData();
			$data  = $model->prepareApiData($post);
			echo json_encode($data);
		}
		else
		{
			echo json_encode('No POST options');
		}
	}
}