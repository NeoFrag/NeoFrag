<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Actions;

class Create extends \NF\NeoFrag\Action
{
	protected $_is_create = TRUE;

	protected $_title     = 'Ajouter';
	protected $_icon      = 'fas fa-plus';
	protected $_color     = 'primary';

	public function __button()
	{
		if ($button = parent::__button())
		{
			return $button	->outline(FALSE)
							->tooltip('')
							->title($this->_title);
		}
	}

	protected function action($model)
	{
		$form = $model	->form2()
						->success(function($model){
							$model->create();

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
			return $form->modal($this->_title, ($this->_icon ?: $model::$icon).' text-'.$this->_color)
						->cancel();
		}
		else
		{
			return $form->panel();
		}
	}
}
