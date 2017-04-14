<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Models;

use NF\NeoFrag\Loadables\Model2;

class User_Profile extends Model2
{
	static public function __schema()
	{
		return [
			'user'          => self::field()->primary()->depends('user'),
			'first_name'    => self::field()->text(100),
			'last_name'     => self::field()->text(100),
			'avatar'        => self::field()->file(),
			'signature'     => self::field()->text(),
			'date_of_birth' => self::field()->datetime()->null(),
			'sex'           => self::field()->enum('female', 'male')->null(),
			'location'      => self::field()->text(100),
			'quote'         => self::field()->text(100),
			'website'       => self::field()->text(100)
		];
	}
}
