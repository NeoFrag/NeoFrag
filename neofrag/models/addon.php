<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Models;

use NF\NeoFrag\Loadables\Model2;

class Addon extends Model2
{
	static public function __schema()
	{
		return [
			'id'   => self::field()->primary(),
			'type' => self::field()->depends('addon_type')->null(),
			'name' => self::field()->text(100),
			'data' => self::field()->serialized()
		];
	}

	static public function __url($addon)
	{
		return url_title($addon->name);
	}

	public function addon()
	{
		return @NeoFrag()->{$this->type() ? $this->type->name : 'addon'}($this->name, $this);
	}

	public function get($type, $name = NULL, $load = TRUE)
	{
		static $types = [];

		if (!$types)
		{
			$types = $this	->db()
							->select('name', 'id')
							->from('nf_addon_type')
							->index();
		}

		$finder = $this->collection()->where('type_id', $types[$type]);

		if ($name)
		{
			return $finder	->where('name', $name)
							->row()
							->addon_if($load);
		}
		else
		{
			return $finder->array()->each_if($load, 'addon')->filter();
		}
	}

	public function controller()
	{
		if ($this->type() && ($controller = @$this->module('addons')->controller('addons/'.$this->type->name)))
		{
			return $controller;
		}
	}
}
