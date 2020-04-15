<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Forms;

abstract class Multiple extends Labelable
{
	protected $_data = [];
	protected $_multiple;

	public function __invoke($name)
	{
		parent::__invoke($name);

		$this->_check[1] = function($post, &$data){
			if ($this->_multiple)
			{
				$data[$this->_name] = [];

				if (isset($post[$this->_name]))
				{
					foreach ($post[$this->_name] as $value)
					{
						if (isset($this->_data[$value]))
						{
							$data[$this->_name][] = $value;
						}
					}
				}

				if ($this->_required && empty($data[$this->_name]))
				{
					$this->_errors[] = $this->lang('Veuillez remplir ce champ');
				}

				$this->_value = $data[$this->_name];
			}
			else
			{
				$this->_value = $data[$this->_name] = '';

				if (array_key_exists($this->_name, $post) && array_key_exists($post[$this->_name], $this->_data))
				{
					$this->_value = $data[$this->_name] = $post[$this->_name];
				}
				else if ($this->_required)
				{
					$this->_errors[] = $this->lang('Veuillez remplir ce champ');
				}
			}
		};

		return $this;
	}

	public function data($data)
	{
		if (is_a($data, 'NF\NeoFrag\Libraries\Collection'))
		{
			$this->_data = [];

			foreach ($data->get() as $row)
			{
				$row = $row->__toArray();
				$id  = $row['id'];
				unset($row['id']);

				$this->_data[$id] = array_values($row);
			}
		}
		else if (method_exists($data, '__toArray'))
		{
			$this->_data = $data->__toArray();
		}
		else
		{
			$this->_data = $data;
		}

		return $this;
	}
}
