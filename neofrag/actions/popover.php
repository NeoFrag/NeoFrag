<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Actions;

class Popover extends \NF\NeoFrag\Action
{
	protected function button($model)
	{
		return parent	::button()
						->title($model)
						->popover_ajax($this->url());
	}

	protected function action($model)
	{
		return $model->view('popovers/'.$model->__name);
	}
}
