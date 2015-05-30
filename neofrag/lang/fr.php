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

$lang = array(
	'admins'   => 'Administrateurs',
	'members'  => 'Membres',
	'visitors' => 'Visiteurs'
);

$lang['locale'] = array(
	'fr_FR.UTF8',
	'fr.UTF8',
	'fr_FR.UTF-8',
	'fr.UTF-8',
	'French_France.1252'
);

$lang['time_long']       = '%H:%M:%S';
$lang['time_short']      = '%H:%M';

$lang['date_long']       = '%A %e %B %Y';
$lang['date_short']      = '%d/%m/%Y';

$lang['date_time_long']  = $lang['date_long'].', '.$lang['time_short'];
$lang['date_time_short'] = $lang['date_short'].' '.$lang['time_short'];

if (!function_exists('date2sql'))
{
	function date2sql(&$date)
	{
		if (preg_match('#(\\d{2})/(\\d{2})/(\\d{4})#', $date, $match))
		{
			$date = implode('-', array_reverse(array_offset_left($match)));
		}
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/lang/fr.php
*/