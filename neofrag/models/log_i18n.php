<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Models;

use NF\NeoFrag\Loadables\Model2;

class Log_I18n extends Model2
{
	static public function __schema()
	{
		return [
			'id'       => self::field()->primary(),
			'language' => self::field()->text(2),
			'key'      => self::field()->text(32),
			'locale'   => self::field()->text(),
			'file'     => self::field()->text(100)
		];
	}
}
