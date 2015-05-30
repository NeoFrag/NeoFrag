<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

function is_authorized($addon_name, $action, $addon_id = 0)
{
	if (NeoFrag::loader()->user('admin'))
	{
		return TRUE;
	}
	
	static $all_permissions;
	
	if (is_null($all_permissions))
	{
		$all_permissions = array();
		
		$permissions = NeoFrag::loader()->db->select('entity_id', 'type', 'authorized', 'action', 'addon_id', 'addon')
								->from('nf_permissions p')
								->join('nf_permissions_details d', 'p.permission_id = d.permission_id')
								->order_by('type ASC, authorized DESC')
								->get();
								
		foreach ($permissions as $permission)
		{
			$all_permissions[$permission['addon']][$permission['action']][$permission['addon_id']][] = array(
				'entity_id'  => (int)$permission['entity_id'],
				'type'       => $permission['type'],
				'authorized' => (bool)$permission['authorized'],
			);
		}
	}

	if (isset($all_permissions[$addon_name][$action][$addon_id]))
	{
		$permissions = $all_permissions[$addon_name][$action][$addon_id];
		
		$count_deny = 0;

		foreach ($permissions as $permission)
		{
			if (!$permission['authorized'])
			{
				$count_deny++;
			}
		}

		$authorized = ($count_deny == count($permissions)) && NeoFrag::loader()->user();

		foreach ($permissions as $permission)
		{
			if (($permission['type'] == 'group' && in_array($permission['entity_id'], array_keys(NeoFrag::loader()->groups(NeoFrag::loader()->user('user_id'))))) ||
				($permission['type'] == 'user' && $permission['entity_id'] == NeoFrag::loader()->user('user_id')))
			{
				$authorized = (bool)$permission['authorized'];
			}
		}

		return $authorized;
	}
	else
	{
		return TRUE;
	}
}

function delete_permission($addon, $addon_id)
{
	NeoFrag::loader()->db	->where('addon', $addon)
							->where('addon_id', $addon_id)
							->delete('nf_permissions');
}

function add_permission($addon, $addon_id, $action, $details)
{
	$permission_id = NeoFrag::loader()->db->insert('nf_permissions', array(
		'addon'    => $addon,
		'addon_id' => $addon_id,
		'action'   => $action
	));
	
	foreach ($details as $detail)
	{
		NeoFrag::loader()->db->insert('nf_permissions_details', array(
			'permission_id' => $permission_id,
			'entity_id'     => $detail['entity_id'],
			'type'          => $detail['type'],
			'authorized'    => $detail['authorized']
		));
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/helpers/permissions.php
*/