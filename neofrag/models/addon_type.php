<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Models;

use NF\NeoFrag\Loadables\Model2;

class Addon_Type extends Model2
{
	static public function __schema()
	{
		return [
			'id'   => self::field()->primary(),
			'name' => self::field()->text(100)
		];
	}

	public function label($multi = FALSE)
	{
		list($title, $title2, $icon, $color) = forward_static_call_array([$this->name, '__label'], []);
		return parent::label($multi ? $title : $title2, $icon, $color);
	}

	public function icon()
	{
		list(,,$icon) = forward_static_call_array([$this->name, '__label'], []);
		return $icon;
	}
}
