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

	if (is_windows())
	{
		$format = preg_replace('#(?<!%)((?:%%)*)%e#', '\1%#d', $format);
	}

	return utf8_string(ucfirst(preg_replace('/ +/', ' ', strtolower(strftime($format, $timestamp)))));
}

function time_span($timestamp)
{
	if (!is_a($timestamp, 'NF\NeoFrag\Libraries\Date') && !is_numeric($timestamp))
	{
		$timestamp = strtotime($timestamp);
	}

	return (string)NeoFrag()->date($timestamp);
}
