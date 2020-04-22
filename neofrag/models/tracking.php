<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Models;

use NF\NeoFrag\Loadables\Model2;

class Tracking extends Model2
{
	static public function __schema()
	{
		return [
			'id'       => self::field()->primary(),
			'user'     => self::field()->depends('user/user')->default(NeoFrag()->user),
			'model'    => self::field()->text(100)->null(),
			'model_id' => self::field()->int()->null(),
			'date'     => self::field()->datetime()
		];
	}
}
