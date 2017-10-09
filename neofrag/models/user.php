<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Models;

use NF\NeoFrag\Loadables\Model2;

class User extends Model2
{
	static public function __schema()
	{
		return [
			'id'                 => self::field()->primary(),
			'username'           => self::field()->text(100),
			'password'           => self::field()->text(34),
			'salt'               => self::field()->text(32),
			'email'              => self::field()->text(100),
			'registration_date'  => self::field()->datetime(),
			'last_activity_date' => self::field()->datetime()->null(),
			'admin'              => self::field()->bool(),
			'language'           => self::field()->depends('addon', '')->null(),
			'data'               => self::field()->serialized(),
			'deleted'            => self::field()->bool()
		];
	}

	public function profile()
	{
		return $this->model2('user_profile', $this->id);
	}

	public function is_online()
	{
		if (!property_exists($this, 'online'))
		{
			if ($this->user->id == $this->id)
			{
				$this->online = TRUE;
			}
			else if ($this->id)
			{
				$this->online = $this->db	->select('MAX(last_activity) > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')
											->from('nf_sessions')
											->where('user_id', $this->id)
											->row();
			}
			else
			{
				$this->online = FALSE;
			}
		}

		return $this->online;
	}

	public function groups()
	{
		return parent::groups($this->id);
	}

	public function link($user_id = 0, $username = '', $prefix = '')
	{
		if (!$user_id)
		{
			$user_id  = $this->id;
			$username = $this->username;
		}

		if (!$username)
		{
			$username = $this->db->select('username')->from('nf_user')->where('id', $user_id)->row();
		}

		if (!$user_id || !$username)
		{
			return '';
		}

		return '<a class="user-profile" data-user-id="'.$user_id.'" data-username="'.url_title($username).'" href="'.url('user/'.$user_id.'/'.url_title($username)).'">'.$prefix.$username.'</a>';
	}

	public function avatar()
	{
		return $this->view('user/avatar');
	}

	public function token()
	{
		$token = $this->module('user')->model2('token')->set('user', $this);

		do
		{
			$token->set('id', unique_id());
		}
		while (!$token->create());


		return $token->id;
	}
}
