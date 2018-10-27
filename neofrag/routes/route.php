<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Routes;

use NF\NeoFrag\NeoFrag;

class Route extends NeoFrag
{
	protected $_check;

	public function check($check = NULL)
	{
		if ($check)
		{
			$this->_check = $check;
			return $this;
		}
		else
		{
			return $this->_check ?: function($model){
				return TRUE;
			};
		}
	}
}
