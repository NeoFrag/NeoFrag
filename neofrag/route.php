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
		if (in_array($name, ['create', 'read', 'update', 'delete']))
		{
			$this->_crud[$name] = $route = NeoFrag()->___load('', 'routes/'.$name, $args);
			return $route;
		}

		return parent::__call($name, $args);
	}

	public function button_create()
	{
		if ($this->_check('create'))
		{
			return parent::button_create()->modal_ajax($this->_url().'/add');
		}
	}

	public function button_read()
	{
		if ($this->_check('read'))
		{
			return parent::button()->popover_ajax($this->_url().'/info/'.$this->_model->url());
		}
	}

	public function button_update()
	{
		if ($this->_check('update'))
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
		if ($this->_check('delete'))
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

		if (	in_array($method, ['info', 'edit', 'delete']) &&
				($model = $model->read(array_shift($args))) &&
				!$model->check(array_shift($args))
			)
		{
			$this->error();
		}

		$actions = [
			'add'    => 'create',
			'info'   => 'read',
			'edit'   => 'update',
			'delete' => 'delete'
		];

		if (array_key_exists($method, $actions) && ($action = $actions[$method]) && $this->_check($action))
		{
			return $this->_crud[$action]->__execute($model);
		}
	}

	protected function _check($action)
	{
		return array_key_exists($action, $this->_crud) && call_user_func_array($this->_crud[$action]->check(), [$this->_model]);
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

		$caller = $this->_model->__caller != NeoFrag() ? $this->_model->__caller : $this->output->module();

		$url[] = $caller->info()->name;

		if ($this->_name)
		{
			$url[] = $this->_name;
		}

		return implode('/', $url);
	}
}
