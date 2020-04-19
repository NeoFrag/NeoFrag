<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag;

abstract class Library extends NeoFrag
{
	private $__id;
	protected $__caller;

	public function __construct($caller)
	{
		$this->__caller = $caller;
	}

	public function __invoke()
	{
		return $this;
	}

	public function __sleep()
	{
		return array_filter(array_keys(get_object_vars($this)), function($a){
			return $a[0] == '_';
		});
	}

	public function __wakeup()
	{
		$this->__caller = NeoFrag();
	}

	public function __id($id = NULL)
	{
		if ($id !== NULL)
		{
			$this->__id = $id;
		}
		else if ($this->__id === NULL)
		{
			$this->__id = $this->crypt->hash(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));

			if (preg_match('/^\d/', $this->__id))
			{
				$this->__id = '_'.$this->__id;
			}
		}

		return $this->__id;
	}
}
