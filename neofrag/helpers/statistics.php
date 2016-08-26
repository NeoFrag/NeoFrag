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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

function statistics($name, $value = NULL, $callback = NULL)
{
	static $statistics;
	
	if ($statistics === NULL)
	{
		foreach (NeoFrag::loader()->db->from('nf_statistics')->get() as $stat)
		{
			$statistics[$stat['name']] = $stat['value'];
		}
	}
	
	if (func_num_args() > 1)
	{
		if (isset($statistics[$name]))
		{
			if ($callback === NULL || call_user_func($callback, $value, $statistics[$name]))
			{
				NeoFrag::loader()->db	->where('name', $name)
										->update('nf_statistics', [
											'value' => $value
										]);
			}
		}
		else
		{
			NeoFrag::loader()->db->insert('nf_statistics', [
				'name'  => $name,
				'value' => $value
			]);
		}
	}
	else
	{
		return $statistics[$name];
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/helpers/statistics.php
*/