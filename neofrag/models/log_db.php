<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Models;

use NF\NeoFrag\Loadables\Model2;

class Log_Db extends Model2
{
	const DB  = 'logs';
	const LOG = NULL;

	static public function __schema()
	{
		return [
			'id'        => self::field()->primary(),
			'date'      => self::field()->datetime(),
			'action'    => self::field()->enum(0, 1, 2),//create - update - delete
			'model'     => self::field()->text(100),
			'primaries' => self::field()->text(100)->null(),
			'data'      => self::field()->serialized()
		];
	}
}
