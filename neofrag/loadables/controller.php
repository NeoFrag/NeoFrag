<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Loadables;

use NF\NeoFrag\NeoFrag;

abstract class Controller extends NeoFrag implements \NF\NeoFrag\Loadable
{
	protected $_caller;

	public function __construct($caller)
	{
		$this->__caller = $caller;
	}

	public function has_method($name)
	{
		$r = new \ReflectionClass($this);

		try
		{
			$method = $r->getMethod($name);
			return $method->class == ($class = get_class($this)) || substr($class, 0, 2) == 'o_' && substr($class, 2) == $method->class;
		}
		catch (\ReflectionException $error)
		{

		}
	}

	public function method($name, $args = [])
	{
		if (!is_array($args))
		{
			if ($args === NULL)
			{
				$args = [];
			}
			else
			{
				$args = [$args];
			}
		}

		ob_start();
		$result = call_user_func_array([$this, $name], $args);
		$output = ob_get_clean();

		if (!empty($result))
		{
			echo $output;
			return $result;
		}
		else
		{
			return $output;
		}
	}

	public function is_authorized($action)
	{
		return $this->access($this->load->caller->name, $action);
	}
}
