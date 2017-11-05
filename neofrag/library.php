<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag;

class Library extends NeoFrag
{
	protected $__caller;

	public function __construct($caller)
	{
		$this->__caller = $caller;
	}

	public function __sleep()
	{
		return array_filter(array_keys(get_object_vars($this)), function($a){
			return $a[0] == '_';
		});
	}

	public function __id($id = NULL)
	{
		static $_id      = [];
		static $_classes = [];

		$hash = spl_object_hash($this);

		if ($id || !isset($_id[$hash]))
		{
			if (!isset($_classes[$class = get_called_class()]))
			{
				$_classes[$class] = 0;
			}

			if (!$id)
			{
				$id = md5(implode([get_class($this->__caller), $this->output->data->get('module', 'controller'), $this->output->data->get('module', 'method'), $class, $_classes[$class]++]));
			}

			$_id[$hash] = $id;
		}

		return $_id[$hash];
	}
}
