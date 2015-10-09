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

/**************************************************************************
Translated by NeoFrag community, contributors are:
FoxLey, eResnova
**************************************************************************/

$lang['lang']                = 'Español';

$lang['unfound_translation'] = '{0}Traduction introuvable : %s|{1}Erreur de pluralisation %s';

$lang['locale'] = array(
	'es_ES.UTF8',
	'es.UTF8',
	'es_ES.UTF-8',
	'es.UTF-8',
	'Spanish_Spain.1252'
);

if (!function_exists('date2sql'))
{
	function date2sql(&$date)
	{
		if (preg_match('#(\d{2})/(\d{2})/(\d{4})#', $date, $match))
		{
			$date = implode('-', array_reverse(array_offset_left($match)));
		}
	}
}

/*
NeoFrag Alpha 0.1.3
./neofrag/lang/es.php
*/