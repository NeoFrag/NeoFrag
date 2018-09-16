<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Routes;

use NF\NeoFrag\NeoFrag;

class Read extends NeoFrag
{
	public function __construct()
	{
	}

	public function __execute($model)
	{
		return $model->view('popovers/'.$model->__name);
	}
}
