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
	
	if (!NeoFrag()->url->ajax)
	{
		if (is_object($objects) && is_a($objects, 'Panel'))
		{
			$objects = NeoFrag()->col($objects);
		}
		
		if (is_object($objects) && is_a($objects, 'Col'))
		{
			$objects = NeoFrag()->row($objects);
		}
	}
	
	if (!is_array($objects))
	{
		$objects = [$objects];
	}
	
	foreach ($objects as $i => $object)
	{
		if ($id !== NULL && method_exists($object, 'id'))
		{
			$object->id($i);
		}

		$output .= $object;
	}
	
	return $output;
}

function output($type)
{
	if (in_array($type, ['css', 'js', 'js_load']) && !empty(NeoFrag()->{$type}))
	{
		if ($type == 'css')
		{
			if ($v = (int)NeoFrag()->config->nf_version_css)
			{
				$v = '?v='.$v;
			}

			$output = array_map(function($a) use ($v){
				return '<link rel="stylesheet" href="'.path($a[0].'.css', 'css', $a[2]->paths('assets')).($v ?: '').'" type="text/css" media="'.$a[1].'" />';
			}, NeoFrag()->css);
		}
		else if ($type == 'js')
		{
			$output = array_map(function($a){
				return '<script type="text/javascript" src="'.path($a[0].'.js', 'js', $a[1]->paths('assets')).'"></script>';
			}, NeoFrag()->js);
		}
		else if ($type == 'js_load')
		{
			$output = NeoFrag()->js_load;
		}

		return implode("\r\n", array_unique($output));
	}
}

/*
NeoFrag Alpha 0.1.6
./neofrag/helpers/output.php
*/