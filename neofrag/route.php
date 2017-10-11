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
		$this->load   = $model->load;
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
				$this->_crud[$name] = $args;
				return $this;
			}
			else
			{
				return isset($this->_crud[$name]);
			}
		}

		return call_user_func_array('parent::__call', func_get_args());
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
			return;
		}

		if ($this->_name && $this->_name != array_shift($args))
		{
			return;
		}

		$method = array_shift($args);

		if ($method == 'add')
		{
			$model = $this->_model->read(0);
		}
		else if (	($method == 'edit' || $method == 'delete') &&
					($model = $this->_model->read(array_shift($args)))
			)
		{
			if ($name = array_shift($args))
			{
				if (isset($model->name))
				{
					if ($name != $model->name)
					{
						$this->error();
					}
				}
				else if (isset($model->title))
				{
					if ($name != url_title($model->title))
					{
						$this->error();
					}
				}
				else
				{
					$this->error();
				}
			}
		}

		if ($method == 'add' && $this->create())
		{
			return $model	->form2()
							->success(function($model){
								$model->create();
								refresh();
							})
							->modal($this->_crud['create'][0], $model::$icon);
		}
		else if ($method == 'edit' && $this->update())
		{
			return $model	->form2()
							->success(function($model){
								$model->update();
								refresh();
							})
							->modal($this->_crud['update'][0], $model::$icon);
		}
		else if ($method == 'delete' && $this->delete())
		{
			return $this->form2()
						->delete(is_a($this->_crud['delete'][1], 'closure') ? call_user_func_array($this->_crud['delete'][1], [$model]) : $this->_crud['delete'][1], function() use ($model){
							$model->delete();
							refresh();
						})
						->modal($this->_crud['delete'][0], $model::$icon);
		}
	}

	protected function _url($ajax = TRUE)
	{
		$url = [];

		$url[] = 'admin';

		if ($ajax)
		{
			$url[] = 'ajax';
		}

		$url[] = $this->load->caller->name;

		if ($this->_name)
		{
			$url[] = $this->_name;
		}

		return implode('/', $url);
	}
}
