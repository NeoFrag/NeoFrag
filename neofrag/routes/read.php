<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Routes;

class Read extends Route
{
	public function __construct()
	{
	}

	public function __execute($model)
	{
		return $model->view('popovers/'.$model->__name);
	}
}
