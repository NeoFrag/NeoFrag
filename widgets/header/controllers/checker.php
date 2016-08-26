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

class w_header_c_checker extends Controller_Widget
{
	public function index($settings = [])
	{
		return [
			'align'             => in_array($settings['align'], ['text-left', 'text-right']) ? $settings['align'] : 'text-center',
			'title'             => utf8_htmlentities($settings['title']),
			'description'       => utf8_htmlentities($settings['description']),
			'color-title'       => preg_match($regex = '/^#([a-f0-9]{3}){1,2}$/i', $settings['color-title'])       ? $settings['color-title']       : '',
			'color-description' => preg_match($regex,                              $settings['color-description']) ? $settings['color-description'] : ''
		];
	}
}

/*
NeoFrag Alpha 0.1.1
./widgets/header/controllers/checker.php
*/