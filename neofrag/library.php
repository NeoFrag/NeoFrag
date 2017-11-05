<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag;

abstract class Library extends NeoFrag
{
	static public $ID;

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

	public function save()
	{
		return clone $this;
	}

	public function set_id($id = NULL)
	{
		$this->id = $id ?: md5($this->name.++self::$ID);
		return $this;
	}
}
