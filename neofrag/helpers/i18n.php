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

//Internationalization
function i18n()
{
	global $loader;
	return call_user_func_array([$loader ?: NeoFrag::loader(), 'lang'], func_get_args());
}

//Pluralization
function p11n($locale)
{
	$args     = func_get_args();
	$locale   = array_shift($args);
	$num_args = count($args);
	
	if ($num_args == 0)
	{
		return $locale;
	}

	if (in_string('|', $locale))
	{
		$n      = NULL;
		$locale = explode('|', $locale);
		$count  = count($locale);
		
		foreach ($locale as $i => &$l)
		{
			if (preg_match('/^\{(\d+?)\}|\[(\d+?),(\d+?|Inf)\]/', $l, $match))
			{
				$n = end($match);
				
				if ($n == 'Inf')
				{
					break;
				}
			}
			else if ($n === NULL)
			{
				$l = '[0,1]'.$l;
				$n = 1;
			}
			else if ($i == $count - 1)
			{
				$l = '['.++$n.',Inf]'.$l;
			}
			else
			{
				$l = '{'.++$n.'}'.$l;
			}
		}

		unset($l);
		
		foreach ($locale as $l)
		{
			if (preg_match('/^\{(\d+?)\}(.*)/', $l, $match) && $args[0] == $match[1])
			{
				$locale = $match[2];
				unset($args[0]);
				break;
			}
			else if (preg_match('/^\[(\d+?),(\d+?|Inf)\](.*)/', $l, $match) && $args[0] >= $match[1] && ($match[2] == 'Inf' || $args[0] <= $match[2]))
			{
				$locale = $match[3];
				unset($args[0]);
				break;
			}
		}
		
		if (is_array($locale))
		{
			return FALSE;
		}
	}
	
	array_unshift($args, $locale);
	return call_user_func_array('sprintf', $args);
}

/*
NeoFrag Alpha 0.1.4
./neofrag/helpers/i18n.php
*/