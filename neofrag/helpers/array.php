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

function array_last_key($array)
{
	$keys = array_keys($array);

	return end($keys);
}

function array_last($array)
{
	return end($array);
}

function array_offset_left($array, $offset = 1)
{
	return array_slice($array, $offset);
}

function array_offset_right($array, $length = 1)
{
	return array_slice($array, 0, -$length);
}

/*
NeoFrag Alpha 0.1
./neofrag/helpers/array.php
*/