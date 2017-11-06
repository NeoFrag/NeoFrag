<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

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
	if ($timestamp === NULL)
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
		return NeoFrag()->lang('À l\'instant');
	}
	else if ($diff == strtoseconds('1 seconds'))
	{
		return NeoFrag()->lang('Il y a une seconde|Il y a %d secondes', 1);
	}
	else if ($diff <= strtoseconds('30 seconds'))
	{
		return NeoFrag()->lang('Il y a une seconde|Il y a %d secondes', $diff, $diff);
	}
	else if ($diff < strtoseconds('45 seconds'))
	{
		return NeoFrag()->lang('Il y a une seconde|Il y a %d secondes', 30, 30);
	}
	else if ($diff < strtoseconds('50 seconds'))
	{
		return NeoFrag()->lang('Il y a une seconde|Il y a %d secondes', 45, 45);
	}
	else if ($diff < strtoseconds('55 seconds'))
	{
		return NeoFrag()->lang('Il y a une seconde|Il y a %d secondes', 50, 50);
	}
	else if ($diff < strtoseconds('2 minutes'))
	{
		return NeoFrag()->lang('Il y a environ une minute|Il y a %d minutes', 1);
	}
	else if ($diff <= strtoseconds('59 minutes'))
	{
		return NeoFrag()->lang('Il y a environ une minute|Il y a %d minutes', $diff = floor($diff / 60), $diff);
	}
	else if ($diff < strtoseconds('2 hours'))
	{
		return NeoFrag()->lang('Il y a environ une heure|Il y a %d heures', 1);
	}
	else if ($diff <= strtoseconds('23 hours'))
	{
		return NeoFrag()->lang('Il y a environ une heure|Il y a %d heures', $diff = floor($diff / 3660), $diff);
	}
	else if ($timestamp >= strtotime('yesterday'))
	{
		return NeoFrag()->lang('Hier, à %s', timetostr(NeoFrag()->lang('%H:%M'), $timestamp));
	}
	else if ($timestamp >= strtotime('6 days ago midnight'))
	{
		return NeoFrag()->lang('%s, à %s', ucfirst(timetostr('%A', $timestamp)), timetostr(NeoFrag()->lang('%H:%M'), $timestamp));
	}
	else
	{
		return timetostr(NeoFrag()->lang('%d/%m/%Y %H:%M'), $timestamp);
	}
}
