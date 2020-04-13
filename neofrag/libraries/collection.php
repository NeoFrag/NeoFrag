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
	protected $_filters;
	protected $_aggregates = [];

	public function __invoke($model = NULL)
	{
		if ($model)
		{
			if (!is_a($model, 'NF\NeoFrag\Loadables\Model2'))
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
		if ($name != 'clone' && $this->_db)
		{
			if ($name == 'array')
			{
				return NeoFrag()->array($this->get());
			}
			else if (method_exists($this->array, $name))
			{
				return call_user_func_array([$this->array(), $name], $args);
			}
			else
			{
				$result = call_user_func_array([$this->_db, $name], $args);
				return is_a($result, 'NF\NeoFrag\NeoFrag') ? $this : $result;
			}
		}

		return parent::__call($name, $args);
	}

	public function __clone()
	{
		$this->_db = $this->_db->clone();
	}

	public function model()
	{
		return $this->_model;
	}

	public function get($cast = TRUE)
	{
		$results = [];

		if ($this->pagination)
		{
			$this->pagination->limit();
		}

		foreach ($this->_db()->get(FALSE) as $result)
		{
			$results[] = $this->_aggregate($result, $cast);
		}

		return $results;
	}

	public function row($cast = TRUE)
	{
		return $this->_aggregate($this->_db()->row(FALSE), $cast);
	}

	public function aggregate($name = '', $value = '', $db = NULL)
	{
		if (!func_num_args())
		{
			$this->_aggregates = [];
		}
		else
		{
			if (!$value)
			{
				$value = $name;
				$name = preg_replace('/^.*\./', '', $name);
			}

			$this->_aggregates[$name] = [$value, $db];
		}

		return $this;
	}

	public function paginate($page, $limit = 20)
	{
		if ($this->_filters)
		{
			$this->_filters->check(TRUE);
		}

		$this->pagination = NeoFrag()->pagination($this->_db, $page, $limit);

		return $this;
	}

	public function filters($form = NULL)
	{
		if (func_num_args())
		{
			$this->_filters = $form->collection($this);
			return $this;
		}
		else
		{
			return $this->_filters;
		}
	}

	public function view($view)
	{
		return implode(array_map(function($a) use ($view){
			return $a->view($view);
		}, $this->get()));
	}

	public function update($data)
	{
		return $this->_db->clone()->from('')->update('nf_'.$this->_model->__table.' `_`', $data);
	}

	public function delete()
	{
		return $this->_db->clone()->from('')->delete('_', 'nf_'.$this->_model->__table.' `_`');
	}

	public function count()
	{
		return $this->_db->clone()->count();
	}

	public function tracking()
	{
		$count = $total_unread = 0;

		foreach ($this->get() as $model)
		{
			$count++;

			$unread = FALSE;

			$model->tracking($unread);

			if ($unread)
			{
				$total_unread++;
			}
		}

		if ($count && !$total_unread && NeoFrag()->db()->from('nf_tracking')->where('user_id', NeoFrag()->user->id)->where('model', $this->_model->__table)->count() > 1)
		{
			NeoFrag()	->collection('tracking')
						->where('user_id', NeoFrag()->user->id)
						->where('model',   $this->_model->__table)
						->delete();

			NeoFrag()	->model2('tracking')
						->set('model',    $this->_model->__table)
						->set('model_id', NULL)
						->create();
		}

		return $total_unread;
	}

	protected function _db()
	{
		$select = $this->_db->select() ?: ['_.*'];

		foreach ($this->_aggregates as $name => list($value, $db))
		{
			$select[] = $value.' AS `'.$name.'`';

			if (is_a($db, 'closure'))
			{
				call_user_func_array($db, [$this->_db]);
			}
		}

		return $this->_db->select(...$select)->__invoke();
	}

	protected function _aggregate($data, $cast)
	{
		$select = $this->_db()->select();

		if (in_array('_.*', $select) || $this->_aggregates)
		{
			$object = $this->_model->load($data);

			foreach ($this->_aggregates as $name => $value)
			{
				$object->$name = $data[$name];
			}

			$data = $object;
		}
		else if ($cast && count($data) == 1)
		{
			$data = current($data);
		}

		return $data;
	}
}
