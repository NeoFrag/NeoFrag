<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Models;

use NF\NeoFrag\Loadables\Model2;

class User extends Model2
{
	static public $icon = 'fa-user';

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

	static public function __route($route)
	{
		$route->delete('Suppression', function($user){
			return $user->lang('Êtes-vous sûr(e) de vouloir supprimer l\'utilisateur <b>%s</b> ?', $user->username);
		});
	}

	static public function __check($model, $value)
	{
		return $value != url_title($model->username);
	}

	public function profile()
	{
		return NeoFrag()->model2('user_profile', $this->id);
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
											->from('nf_session')
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
		return $this->groups->user_groups($this->id);
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

		$this->js('user-badge');

		return '<a class="user-badge" data-user-id="'.$user_id.'" data-username="'.url_title($username).'" href="'.url('///user/'.$user_id.'/'.url_title($username)).'">'.$prefix.$username.'</a>';
	}

	public function avatar()
	{
		return $this->html()
					->attr('class', 'avatar')
					->append_attr_if($this->is_online(),  'class', 'online')
					->append_attr_if(!$this->is_online(), 'class', 'offline')
					->content($this->view('user/avatar'));
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

	public function password($password)
	{
		if (NeoFrag()->password->is_valid($password.$this->salt, $this->password, $salt = $this->salt !== ''))
		{
			if (!$salt)
			{
				$this	->set('password', NeoFrag()->password->encrypt($password.($salt = unique_id())))
						->set('salt', $salt)
						->update();
			}

			return TRUE;
		}

		return FALSE;
	}

	public function set_password($password)
	{
		$this	->set('password', NeoFrag()->password->encrypt($password.($salt = unique_id())))
				->set('salt', $salt);

		if ($this())
		{
			$this	->sessions()
					->where_if(NeoFrag()->user() && NeoFrag()->user->id == $this->id, 'id <>', NeoFrag()->session->id)
					->update([
						'user_id' => NULL
					]);
		}

		return $this;
	}

	public function url()
	{
		return $this->id.'/'.url_title($this->username);
	}

	public function sessions()
	{
		return NeoFrag()->collection('session')
						->where('_.user_id', $this->id);
	}

	public function delete()
	{
		$this	->set('deleted', TRUE)
				->update()
				->sessions()
				->update([
					'user_id' => NULL
				]);

		return $this;
	}
}
