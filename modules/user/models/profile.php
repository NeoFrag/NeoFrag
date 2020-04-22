<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Models;

use NF\NeoFrag\Loadables\Model2;

class Profile extends Model2
{
	static public function __schema()
	{
		return [
			'id'            => self::field()->depends('user/user', '')->primary(),
			'first_name'    => self::field()->text(100),
			'last_name'     => self::field()->text(100),
			'avatar'        => self::field()->file(),
			'cover'         => self::field()->file(),
			'signature'     => self::field()->text(),
			'date_of_birth' => self::field()->date()->null(),
			'sex'           => self::field()->enum('female', 'male')->null(),
			'country'       => self::field()->text(100),
			'location'      => self::field()->text(100),
			'quote'         => self::field()->text(100),
			'website'       => self::field()->text(100),
			'linkedin'      => self::field()->text(100),
			'github'        => self::field()->text(100),
			'instagram'     => self::field()->text(100),
			'twitch'        => self::field()->text(100)
		];
	}
}
