<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Core;

use NF\NeoFrag\Core;

class Access extends Core
{
	private $_access = [];

	public function __construct()
	{
		$this->reload();
	}

	public function reload()
	{
		$this->_access = [];

		$all_access = $this->db()	->select('ad.entity', 'ad.type', 'ad.authorized', 'a.action', 'a.id', 'a.module')
									->from('nf_access a')
									->join('nf_access_details ad', 'a.access_id = ad.access_id')
									->order_by('type <> "user"', 'authorized DESC')
									->get();

		if ($all_access === NULL)
		{
			header('HTTP/1.0 503 Service Unavailable');
			exit('Database is empty');
		}

		foreach ($all_access as $access)
		{
			if ($access['entity'])
			{
				$this->_access[$access['module']][$access['action']][$access['id']][] = [
					'entity'     => $access['entity'],
					'type'       => $access['type'],
					'authorized' => (bool)$access['authorized']
				];
			}
			else
			{
				$this->_access[$access['module']][$access['action']][$access['id']] = TRUE;
			}
		}
	}

	public function __invoke($module, $action, $id = 0, $group_id = NULL, $user_id = NULL)
	{
		$access = isset($this->_access[$module][$action][$id]) ? $this->_access[$module][$action][$id] : FALSE;

		if (	$group_id == 'admins' ||
				($user_id !== NULL && in_array('admins', $this->groups($user_id))) ||
				($group_id === NULL && $user_id === NULL && $this->user->admin) ||
				$access === TRUE
			)
		{
			return TRUE;
		}
		else if (!$access)
		{
			return FALSE;
		}

		$authorized = array_fill(0, 2, 0);

		foreach ($access as $permission)
		{
			if ($permission['type'] == 'group')
			{
				$authorized[$permission['authorized']]++;
			}
		}

		$user_groups = $user_id || $this->user() ? $this->groups($user_id ?: $this->user->id) : ['visitors'];
		$default     = array_sum($authorized) ? $authorized[0] > $authorized[1] : TRUE;
		$groups      = [];

		foreach ($access as $permission)
		{
			if ($permission['type'] == 'group' && $group_id === NULL && in_array($permission['entity'], $user_groups))
			{
				$groups[$permission['entity']] = (bool)$permission['authorized'];
			}
			else if ($permission['type'] == 'group' && $group_id == $permission['entity'])
			{
				return (bool)$permission['authorized'];
			}
			else if ($permission['type'] == 'user'  && $group_id === NULL && $permission['entity'] == ($user_id ?: $this->user->id))
			{
				return (int)(bool)$permission['authorized'];
			}
		}

		if ($group_id !== NULL)
		{
			return $default;
		}

		if ($user_id || $this->user())
		{
			foreach (array_keys($this->groups()) as $group)
			{
				if ($group != 'admins' && in_array($group, $user_groups))
				{
					return isset($groups[$group]) ? $groups[$group] : $default;
				}
			}
		}
		else
		{
			return isset($groups['visitors']) ? $groups['visitors'] : $default;
		}
	}

	public function count($module, $action, $id = 0)
	{
		$count = array_fill(0, 2, 0);

		foreach ($this->db->select('id')->from('nf_user')->where('deleted', FALSE)->get() as $user_id)
		{
			$access = $this($module, $action, $id, NULL, $user_id);
			$count[(int)$access]++;
		}

		$output = [];

		if (!empty($count[1]))
		{
			$output[] = '<span class="text-success" data-toggle="tooltip" title="'.$this->lang('Membres autorisés').'" data-original-title="">'.icon('fas fa-check').' '.$count[1].'</span>';
		}

		if (!empty($count[0]))
		{
			$output[] = '<span class="text-danger" data-toggle="tooltip" title="'.$this->lang('Membres exclus').'">'.icon('fas fa-ban').' '.$count[0].'</span>';
		}

		if (!$this($module, $action, $id, 'visitors'))
		{
			$output[] = '<span class="text-info" data-toggle="tooltip" title="'.$this->lang('Visiteurs exclus').'">'.icon('far fa-eye-slash').'</span>';
		}

		return implode(str_repeat('&nbsp;', 3), $output);
	}

	public function init($module_name, $type = 'default', $id = 0)
	{
		$module = $this->module($module_name);
		$access = $module->get_permissions($type);

		if (!empty($access['init']))
		{
			foreach ($access['init'] as $action => $groups)
			{
				$access_id = $this->db->insert('nf_access', [
					'module' => $module_name,
					'action' => $action,
					'id'     => $id
				]);

				foreach ($groups as $group)
				{
					list($entity, $authorized) = $group;

					$this->db->insert('nf_access_details', [
						'access_id'  => $access_id,
						'entity'     => $entity,
						'type'       => 'group',
						'authorized' => $authorized
					]);
				}
			}

			$this->reload();
		}

		return $this;
	}

	public function delete($module, $id = 0)
	{
		$this->db	->where('module', $module)
					->where('id', $id)
					->delete('nf_access');

		return $this;
	}

	public function revoke($group_id)
	{
		$this->db	->where('entity', $group_id)
					->where('type', 'group')
					->delete('nf_access_details');

		return $this;
	}

	public function admin()
	{
		static $allowed;

		if ($allowed === NULL)
		{
			$allowed = FALSE;

			if ($this->user->admin)
			{
				$allowed = TRUE;
			}
			else
			{
				foreach ($this->model2('addon')->get('module') as $module)
				{
					if ($module->is_authorized())
					{
						$allowed = TRUE;
						break;
					}
				}
			}
		}

		return $allowed;
	}
}
