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

function url($url = '')
{
	return NeoFrag::loader()->config->base_url.$url;
}

function redirect($location = '')
{
	header('Location: '.url($location));
	exit;
}

function redirect_back($default = '')
{
	header('Location: '.url(NeoFrag::loader()->session->get_back() ?: $default));
	exit;
}

function refresh()
{
	header('Location: '.$_SERVER['REQUEST_URI']);
	exit;
}

function urltolink($url)
{
	return '<a href="'.$url.'">'.parse_url($url, PHP_URL_HOST).'</a>';
}

/*
NeoFrag Alpha 0.1
./neofrag/helpers/location.php
*/