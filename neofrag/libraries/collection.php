<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Collection extends Library
{
	public $pagination;

	protected $_db;
	protected $_model;
	protected $_aggregates = [];

	public function __invoke($model = NULL)
	{
		if ($model)
		{
			if (!is_a($model, 'NF\\NeoFrag\\Loadables\\Model2'))
			{
				$model = $this->__caller->model2($model);
			}

			$this->_model = $model;
			$this->_db    = $model->db();
		}

		return $this;
	}

	public function __call($name, $args)
	{
		if ($this->_db)
		{
			$result = call_user_func_array([$this->_db, $name], $args);
			return $this->_db && is_a($result, 'NF\\NeoFrag\\NeoFrag') ? $this : $result;
		}

		return parent::__call($name, $args);
	}

	public function model()
	{
		return $this->_model;
	}

	public function get()
	{
		$results = [];

		if ($this->pagination)
		{
			$this->pagination->limit();
		}

		foreach ($this->_db()->get() as $result)
		{
			$results[] = $this->_aggregate($result);
		}

		return $results;
	}

	public function row()
	{
		return $this->_aggregate($this->_db()->row());
	}

	public function aggregate($name, $value)
	{
		$this->_aggregates[$name] = $value;
		return $this;
	}

	public function paginate($page, $limit = 20)
	{
		$this->pagination = parent::__call('pagination', [$this->_db, $page, $limit]);
		return $this;
	}

	public function view($view)
	{
		return implode(array_map(function($a) use ($view){
			return $a->view($view);
		}, $this->get()));
	}

	protected function _db()
	{
		$select = $this->_db->select() ?: ['_.*'];

		if ($this->_aggregates)
		{
			foreach ($this->_aggregates as $name => $value)
			{
				$select[] = $value.' AS `'.$name.'`';
			}

			$this->_db->group_by('_.id');
		}

		return call_user_func_array([$this->_db, 'select'], $select)->__invoke();
	}

	protected function _aggregate($data)
	{
		$object = $this->_model->load($data);

		foreach ($this->_aggregates as $name => $value)
		{
			$object->$name = $data[$name];
		}

		return $object;
	}
}
