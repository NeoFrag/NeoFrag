<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_live_editor_c_checker extends Controller_Module
{
	public function index()
	{
		if (!$this->user('admin'))
		{
			throw new Exception(NeoFrag::UNAUTHORIZED);
		}
		
		return [];
	}
}
