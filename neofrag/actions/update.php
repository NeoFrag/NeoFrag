<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Actions;

class Update extends \NF\NeoFrag\Action
{
	protected $_title = 'Éditer';
	protected $_icon  = 'fas fa-pencil-alt';
	protected $_color = 'primary';

	protected function action($model)
	{
		$form = $model	->form2()
						->success(function($model){
							$model->update();

							if ($this->_notify)
							{
								notify($this->_notify);
							}

							if ($this->_redirect)
							{
								redirect($this->_redirect);
							}
							else
							{
								refresh();
							}
						});

		if ($this->_ajax)
		{
			return $form->modal($this->title($model), ($this->_icon ?: $model::$icon).' text-'.$this->_color)
						->cancel();
		}
		else
		{
			return $form->panel();
		}
	}

	protected function title($model)
	{
		return $this->lang('Édition de %s', $model);
	}
}
