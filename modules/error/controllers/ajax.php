<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_error_c_ajax extends Controller_Module
{
	public function index()
	{
		header('HTTP/1.0 404 Not Found');
		echo 'error';
	}

	public function unauthorized()
	{
		header('HTTP/1.0 401 Unauthorized');
		echo 'unauthorized';
	}
}
