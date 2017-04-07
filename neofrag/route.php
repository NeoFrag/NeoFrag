<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag;

class Route extends NeoFrag
{
	protected $_model;
	protected $_name;
	protected $_title;
	protected $_crud = [];

	public function __construct($model)
	{
		$this->_model = $model;
	}

	public function name($title, $name = '')
	{
		$this->_name  = $name;
		$this->_title = $title;
		return $this;
	}

	public function title()
	{
		return $this->_title;
	}

	public function __call($name, $args)
	{
		if (in_array($name, ['create', 'update', 'delete']))
		{
			if ($args)
			{
				$this->_crud[$name] = NeoFrag()->___load('', 'routes/'.$name, $args);
				return $this;
			}
			else
			{
				return isset($this->_crud[$name]);
			}
		}

		return parent::__call($name, $args);
	}

	public function button_create()
	{
		if ($this->create())
		{
			return parent::button_create()->modal_ajax($this->_url().'/add');
		}
	}

	public function button_update()
	{
		if ($this->update())
		{
			return parent::button_update()->modal_ajax($this->_url().'/edit/'.$this->_model->url());
		}
		else
		{
			return parent::button_update($this->_url(FALSE).'/edit/'.$this->_model->url());
		}
	}

	public function button_delete()
	{
		if ($this->delete())
		{
			return parent::button_delete()->modal_ajax($this->_url().'/delete/'.$this->_model->url());
		}
	}

	public function execute($args)
	{
		if (!$this->url->admin || !$this->url->ajax)
		{
			//return;
		}

		if ($this->_name && $this->_name != array_shift($args))
		{
			return;
		}

		$method = array_shift($args);

		$model = $this->_model;

		if (($method == 'edit' || $method == 'delete') && ($model = $model->read(array_shift($args))) && $model())
		{
			if ($name = array_shift($args))
			{
				if ($model->static___check($model, $name))
				{
					$this->error();
				}
			}
		}

		$actions = [
			'add'    => 'create',
			'edit'   => 'update',
			'delete' => 'delete'
		];

		if (array_key_exists($method, $actions) && ($action = $actions[$method]) && $this->$action())
		{
			return $this->_crud[$action]->__execute($model);
		}
	}

	protected function _url($ajax = TRUE)
	{
		$url = [];

		if ($this->url->admin)
		{
			$url[] = 'admin';
		}

		if ($ajax)
		{
			$url[] = 'ajax';
		}

		// $url[] = $this->_model->__caller->info()->name;
		$url[] = $this->output->module()->info()->name;

		if ($this->_name)
		{
			$url[] = $this->_name;
		}

		return implode('/', $url);
	}
}
