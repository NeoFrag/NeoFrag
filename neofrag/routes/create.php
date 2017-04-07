<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Routes;

use NF\NeoFrag\NeoFrag;

class Delete extends NeoFrag
{
	protected $_title;

	public function __construct($title)
	{
		$this->_title = $title;
	}

	public function __execute($model)
	{
		return $model	->form2()
						->success(function($model){
							$model->create();
							refresh();
						})
						->modal($this->_title, $model::$icon);
	}
}
