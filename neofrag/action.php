<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag;

use NF\NeoFrag\NeoFrag;

abstract class Action extends NeoFrag
{
	protected $_ajax      = TRUE;
	protected $_admin     = TRUE;
	protected $_is_create = FALSE;
	protected $_model;
	protected $_name;
	protected $_title;
	protected $_icon;
	protected $_color;
	protected $_notify;
	protected $_redirect;

	public function __construct($model, $name)
	{
		$this->__caller = $model->__caller;
		$this->_model   = $model;
		$this->_name    = $name;
	}

	public function __button()
	{
		if ($this->_is_create || $this->_model() && $this->check($this->_model))
		{
			return $this->button('')
						->compact()
						->outline()
						->tooltip($this->_title)
						->color($this->_color)
						->exec(function($button){
							if (!$button->icon())
							{
								$button->icon($this->_icon);
							}
						})
						->{$this->_ajax ? 'modal_ajax' : 'url'}($this->url());
		}
	}

	public function __check()
	{
		return  $this->url->ajax == $this->_ajax &&
				(!$this->_admin || $this->url->admin) &&
				($this->_is_create || $this->_model() && $this->check($this->_model));
	}

	public function __toString()
	{
		return (string)$this->action($this->_model);
	}

	protected function module($name = NULL)
	{
		return $name === NULL ? $this->__caller : parent::module($name);
	}

	protected function modal($title = '')
	{
		$model = $this->_model;//TODO 5.6 compatibility
		return parent::modal($title ?: $this->_title, ($this->_icon ?: $model::$icon).' text-'.$this->_color);
	}

	protected function check($model)
	{
		return TRUE;
	}

	protected function url()
	{
		return $this->array()
					->append('admin')
					->append_if($this->_ajax, 'ajax')
					->append($this->_model->__caller->info()->name)
					->append($this->_model->__name)
					->append($this->_name)
					->each('url_title')
					->append_if(!$this->_is_create, $this->_model->url())
					->implode('/');
	}
}
