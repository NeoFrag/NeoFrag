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

function display($objects, $id = NULL)
{
	$output = '';
	
	if (is_object($objects) && is_a($objects, 'Panel'))
	{
		$objects = array(
			new Row(
				new Col($objects)
			)
		);
		
	}
	else if (is_object($objects) && is_a($objects, 'Row'))
	{
		$objects = array($objects);
	}
	
	foreach ($objects as $i => $object)
	{
		if (is_object($object))
		{
			$output .= $object->display(!is_null($id) ? $i : NULL);
		}
		else
		{
			$output .= $object;
		}
	}
	
	return $output;
}

/*
NeoFrag Alpha 0.1
./neofrag/helpers/output.php
*/