<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Loadables;

use NF\NeoFrag\NeoFrag;

abstract class Model2 extends NeoFrag implements \NF\NeoFrag\Loadable
{
	static protected $_schemas = [];
	static protected $_objects = [];

	static public function __load($caller, $args = [])
	{
		$name = array_shift($args);

		$table = [];

		if (method_exists($caller, 'info'))
		{
			$table[] = $caller->info()->name;
		}

		if (!$table || $table[0] != $name)
		{
			$table[] = $name;
		}

		if (isset(static::$_objects[$table = implode('_', $table)][$id = static::_id($args)]))
		{
			return static::$_objects[$table][$id];
		}

		return $caller->___load('models', $name, [$caller, $name, $table, $id]);
	}

	static function _id($args)
	{
		if (array_key_exists(1, $args))
		{
			$id = serialize($args);
		}
		else if ($args)
		{
			$id = $args[0];
		}

		if (!isset($id))
		{
			$id = 0;
		}

		return $id;
	}

	static protected function __schema()
	{
	}

	static protected function field()
	{
		return new \NF\NeoFrag\Field;
	}

	public $__name      = '';
	public $__table     = '';

	protected $_id      = 0;
	protected $_data    = [];
	protected $_updates = [];
	protected $_values  = [];

	public function __construct($caller, $name, $table, $id)
	{
		$this->__caller = $caller;
		$this->__name   = $name;
		$this->__table  = $table;

		if (array_filter($primaries = (array)$id) && ($values = $this->_get_by_primaries($primaries)->from($this->_table())->row()))
		{
			$this->_data($values, $id);
		}
	}

	public function __isset($name)
	{
		return (bool)$this->_schema($name);
	}

	public function __get($name)
	{
		if ($field = $this->_schema($name))
		{
			return $this->_value($field);
		}

		return $this->__caller->$name;
	}

	public function __unset($name)
	{
		if ($field = $this->_schema($name))
		{
			unset($this->_updates[$field->i], $this->_values[$field->i]);
		}
	}

	public function __invoke()
	{
		return (bool)$this->_id;
	}

	public function __debugInfo()
	{
		static $objects = [];

		$values = [];

		if (!isset($objects[$this->_table()][$this->_id]))
		{
			$objects[$this->_table()][$this->_id] = TRUE;

			$values['id']   = $this->_id;
			$values['data'] = $this->_data;

			if ($this->_updates)
			{
				$values['update'] = $this->_updates;
			}

			foreach ($this->_schema() as $name => $field)
			{
				$values['values'][$name] = $this->_value($field);
			}
		}

		return $values;
	}

	public function __toArray()
	{
		$values = [];

		foreach ($this->_schema() as $name => $field)
		{
			$values[$name] = $this->_value($field);
		}

		foreach (get_object_vars($this) as $name => $value)
		{
			if ($name[0] != '_')
			{
				$values[$name] = $value;
			}
		}

		return $values;
	}

	public function __clone()
	{
		$this->_id   = 0;
		$this->_data = $this->_updates = $this->_values = [];
	}

	public function load($values)
	{
		if (!$values)
		{
			return $this->_id ? clone $this : $this;
		}

		$id = [];

		foreach ($this->_primaries() as $name => $field)
		{
			$id[] = $values[$field->key($name)];
		}

		if (!isset(static::$_objects[$this->__table][$id = static::_id($id)]))
		{
			$model = clone $this;
			$model->_data($values, $id);
		}

		return static::$_objects[$this->__table][$id];
	}

	public function set($name, $value)
	{
		if ($field = $this->_schema($name))
		{
			$value = $field->raw($value);

			if (!$this->_data || $value !== $this->_data[$field->i])
			{
				$this->_updates[$field->i] = $value;
				unset($this->_values[$field->i]);
			}
		}

		return $this;
	}

	public function collection()
	{
		return NeoFrag()->collection($this);
	}

	public function db()
	{
		return $this->_db()->from($this->_table().' AS `_`');
	}

	public function form2()
	{
		return $this->_data ? $this->load->form2($this->_name, $this) : $this->load->form2($this->_name);
	}

	public function view($view)
	{
		return $this->__caller->view($view, [
			$this->__name => $this
		]);
	}

	public function route()
	{
		$this::__route($route = \NeoFrag()->___load('', 'route', $this));
		return $route;
	}

	public function url()
	{
		$url = [];

		if (isset($this->id))
		{
			$url[] = $this->id;
		}

		if (isset($this->name))
		{
			$url[] = $this->name;
		}
		else if (isset($this->title))
		{
			$url[] = url_title($this->title);
		}

		return implode('/', $url);
	}

	public function create()
	{
		if ($this->_updates && !$this->_data)
		{
			$values = $this->_updates();

			foreach ($this->_schema() as $name => $field)
			{
				if (!array_key_exists($key = $field->key($name), $values))
				{
					$values[$key] = $field->raw();
				}
			}

			if (($auto_id = $this->_db()->insert($this->_table(), $values)) !== NULL)
			{
				$id = $this->_updates = $this->_values = [];

				$primaries = $this->_primaries();

				if ($auto_id && $primaries && array_keys($primaries) == ['id'])
				{
					$values['id'] = $auto_id;
				}

				foreach ($primaries as $name => $field)
				{
					$id[] = $values[$field->key($name)];
				}

				unset(static::$_objects[$this->__table][$this->_id]);

				$this->_data($values, static::_id($id));

				$this->_log('create', $this->_data);

				return $this;
			}
		}
	}

	public function update()
	{
		if ($this->_updates && $this->_data && $this->_get_by_primaries($primaries)->update($this->_table(), $this->_updates()) !== NULL)
		{
			$this->_data = $this->_updates + $this->_data;

			$id = [];

			foreach ($this->_primaries() as $field)
			{
				$id[] = $this->_data[$field->i];
			}

			if ($this->_id != ($id = static::_id($id)))
			{
				unset(static::$_objects[$this->__table][$this->_id]);
				static::$_objects[$this->__table][$id] = $this;
			}

			$this->_log('update', $this->_updates, $primaries);

			$this->_updates = [];

			return $this;
		}
	}

	public function commit()
	{
		if (($result = $this->update()) || ($result = $this->create()))
		{
			return $result;
		}
	}

	public function delete()
	{
		if ($this->_data)
		{
			$this->_get_by_primaries($primaries)->delete($this->_table());

			$this->_log('delete', $this->_data, $primaries);

			unset(static::$_objects[$this->__table][$this->_id]);

			$this->_updates += $this->_data;

			$this->_data = $this->_values = [];

			$this->_id = 0;
		}

		return $this;
	}

	protected function _schema($name = NULL)
	{
		if (!isset(self::$_schemas[$this->__table]))
		{
			self::$_schemas[$this->__table] = $this::__schema();

			$i = 0;

			foreach (self::$_schemas[$this->__table] as $field_name => $field)
			{
				$field->i    = $i++;
				$field->name = $field_name;
			}
		}

		return $name ? (isset(self::$_schemas[$this->__table][$name]) ? self::$_schemas[$this->__table][$name] : NULL) : self::$_schemas[$this->__table];
	}

	protected function _db()
	{
		return NeoFrag()->db(defined('static::DB') ? static::DB : 'default');
	}

	protected function _get_by_primaries(&$primaries = [])
	{
		$output = [];

		$db = $this->_db();

		foreach ($this->_primaries() as $name => $field)
		{
			$db->where($key = $field->key($name), $value = array_key_exists($i = $field->i, $this->_data) ? $this->_data[$i] : array_shift($primaries));
			$output[$key] = $value;
		}

		$primaries = $output;

		return $db;
	}

	protected function _primaries()
	{
		return array_filter($this->_schema(), function($a){
			return $a->is_primary();
		});
	}

	protected function _updates()
	{
		$updates = [];

		foreach ($this->_schema() as $name => $field)
		{
			if (array_key_exists($field->i, $this->_updates))
			{
				$updates[$field->key($name)] = $this->_updates[$field->i];
			}
		}

		return $updates;
	}

	protected function _value($field)
	{
		if (!array_key_exists($key = $field->i, $this->_values))
		{
			if (array_key_exists($key, $this->_updates))
			{
				$this->_values[$key] = $field->value($this, $this->_updates[$key]);
			}
			else if (array_key_exists($key, $this->_data))
			{
				$this->_values[$key] = $field->value($this, $this->_data[$key]);
			}
			else
			{
				$this->_values[$key] = $field->value($this);
			}
		}

		return $this->_values[$key];
	}

	protected function _table()
	{
		return 'nf_'.$this->__table;
	}

	protected function _data($values, $id)
	{
		$completed = TRUE;

		foreach ($this->_schema() as $name => $field)
		{
			if (array_key_exists($key = $field->key($name), $values))
			{
				$this->_data[$field->i] = $values[$key];
			}
			else if (!$field->is_i18n())
			{
				$completed = FALSE;
			}
		}

		if ($completed)
		{
			static::$_objects[$this->__table][$this->_id = $id] = $this;
		}
	}

	protected function _log($action, $data, $primaries = NULL)
	{
		return;

		if (!defined('static::LOG') || static::LOG)
		{
			$actions = [
				'create' => 0,
				'update' => 1,
				'delete' => 2
			];

			if (!$primaries)
			{
				$this->_get_by_primaries($primaries);
			}

			$this	->model2('log_db')
					->action($actions[$action])
					->model($this->__table)
					->primaries(count($primaries) == 1 && isset($primaries['id']) ? $primaries['id'] : serialize($primaries))
					->data($data)
					->create();
		}
	}
}
