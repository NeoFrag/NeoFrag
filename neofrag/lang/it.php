<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

/**************************************************************************
Translated by NeoFrag community, contributors are:
FoxLey, eResnova
**************************************************************************/

$lang['lang']                = 'Italiano';

$lang['unfound_translation'] = '{0}Traduction introuvable : %s|{1}Erreur de pluralisation %s';

$lang['locale'] = [
	'it_IT.UTF8',
	'it.UTF8',
	'it_IT.UTF-8',
	'it.UTF-8',
	'Italian_Italy.1252'
];

if (!function_exists('date2sql'))
{
	function date2sql(&$date)
	{
		if (preg_match('#^(\d{2})/(\d{2})/(\d{4})$#', $date, $match))
		{
			$date = $match[3].'-'.$match[2].'-'.$match[1];
		}
	}
}

if (!function_exists('time2sql'))
{
	function time2sql(&$time)
	{
		if (preg_match('#^(\d{2}):(\d{2})$#', $time, $match))
		{
			$time = $match[1].':'.$match[2].':00';
		}
	}
}


if (!function_exists('datetime2sql'))
{
	function datetime2sql(&$datetime)
	{
		if (preg_match('#^(\d{2})/(\d{2})/(\d{4}) (\d{2}):(\d{2})$#', $datetime, $match))
		{
			$datetime = $match[3].'-'.$match[2].'-'.$match[1].' '.$match[4].':'.$match[5].':00';
		}
	}
}
