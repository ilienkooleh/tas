<?php

namespace Controller;

class Main
{
	/**
	 * Run main page routing.
	 */
	public	function run()
	{
		$action = isset($_GET['page']) ? $_GET['page'] : 'index';

		if ( !method_exists($this, $action) )
		{
			header("HTTP/1.0 404 Not Found");
		}
		else
		{
			$this->$action();
		}
	}

	/**
	 * Method for page rendering.
	 *
	 * @param string $filename
	 * @param array  $vars
	 */
	function renderPhpFile($filename, $vars = null)
	{
		if ( is_array($vars) && !empty($vars) )
		{
			extract($vars);
		}
		ob_start();
		include $filename;
		return ob_get_clean();
	}
}