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

function display($objects, $id = NULL)
{
	$output = '';
	
	if (!NeoFrag::loader()->config->ajax_url)
	{
		if (is_object($objects) && is_a($objects, 'Panel'))
		{
			$objects = new Col($objects);
		}
		
		if (is_object($objects) && is_a($objects, 'Col'))
		{
			$objects = new Row($objects);
		}
	}
	
	if (!is_array($objects))
	{
		$objects = [$objects];
	}
	
	foreach ($objects as $i => $object)
	{
		if (is_object($object))
		{
			$output .= $object->display($id !== NULL ? $i : NULL);
		}
		else
		{
			$output .= $object;
		}
	}
	
	return $output;
}

function output($type)
{
	if (in_array($type, ['css', 'js', 'js_load']) && !empty(NeoFrag::loader()->{$type}))
	{
		if ($type == 'css')
		{
			$output = array_map(function($a){
				return '<link rel="stylesheet" href="'.path($a[0].'.css', 'css', $a[2]['assets']).'" type="text/css" media="'.$a[1].'" />';
			}, NeoFrag::loader()->css);
		}
		else if ($type == 'js')
		{
			$output = array_map(function($a){
				return '<script type="text/javascript" src="'.path($a[0].'.js', 'js', $a[1]['assets']).'"></script>';
			}, NeoFrag::loader()->js);
		}
		else if ($type == 'js_load')
		{
			$output = NeoFrag::loader()->js_load;
		}

		return implode("\r\n", array_unique($output));
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/helpers/output.php
*/