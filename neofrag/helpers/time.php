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

function set_time_zone($time_zone)
{
	if (preg_match('/(\+|-)([0-9]{2}):([0-9]{2})/', $time_zone, $matches))
	{
		list(, $sign, $hours, $minutes) = $matches;
		
		$offset = (($sign == '+') ? 1 : -1) * ((int)$hours * 3600 + (int)$minutes * 60);

		if (($time_zone = timezone_name_from_abbr('', $offset, 0)) === FALSE)
		{
			foreach (timezone_abbreviations_list() as $abbr)
			{
				foreach ($abbr as $zone)
				{
					if (!$zone['dst'] && $zone['offset'] == $offset)
					{
						return date_default_timezone_set($zone['timezone_id']);
					}
				}
			}	
		}
	}
	
	return $time_zone ? date_default_timezone_set($time_zone) : FALSE;
}

function now($timestamp = NULL)
{
	return timetostr('%Y-%m-%d %H:%M:%S', $timestamp);
}

function strtoseconds($string)
{
	return strtotime($string, 0);
}

function timetostr($format, $timestamp = NULL)
{
	if (is_null($timestamp))
	{
		$timestamp = time();
	}

	if (!is_numeric($timestamp))
	{
		$timestamp = strtotime($timestamp);
	}

	if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
	{
		$format = preg_replace('#(?<!%)((?:%%)*)%e#', '\1%#d', $format);
	}

	return utf8_string(ucfirst(strtolower(strftime($format, $timestamp))));
}

function time_span($timestamp)
{
	if (!is_numeric($timestamp))
	{
		$timestamp = strtotime($timestamp);
	}

	$diff = time() - $timestamp;

	if (!$diff)
	{
		return 'À l\'instant';
	}
	else if ($diff == strtoseconds('1 seconds'))
	{
		return 'Il y a une seconde';
	}
	else if ($diff <= strtoseconds('30 seconds'))
	{
		return 'Il y a '.$diff.' secondes';
	}
	else if ($diff < strtoseconds('45 seconds'))
	{
		return 'Il y a 30 secondes';
	}
	else if ($diff < strtoseconds('50 seconds'))
	{
		return 'Il y a 45 secondes';
	}
	else if ($diff < strtoseconds('55 seconds'))
	{
		return 'Il y a 50 secondes';
	}
	else if ($diff < strtoseconds('2 minutes'))
	{
		return 'Il y a environ une minute';
	}
	else if ($diff <= strtoseconds('59 minutes'))
	{
		return 'Il y a '.floor($diff / 60).' minutes';
	}
	else if ($diff < strtoseconds('2 hours'))
	{
		return 'Il y a environ une heure';
	}
	else if ($diff <= strtoseconds('23 hours'))
	{
		return 'Il y a '.floor($diff / 3660).' heures';
	}
	else if ($timestamp >= strtotime('yesterday'))
	{
		return 'Hier, à '.timetostr(NeoFrag::loader()->lang('time_short'), $timestamp);
	}
	else if ($timestamp >= strtotime('6 days ago midnight'))
	{
		return ucfirst(timetostr('%A', $timestamp)).', à '.timetostr(NeoFrag::loader()->lang('time_short'), $timestamp);
	}
	else
	{
		return timetostr(NeoFrag::loader()->lang('date_time_short'), $timestamp);
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/helpers/time.php
*/