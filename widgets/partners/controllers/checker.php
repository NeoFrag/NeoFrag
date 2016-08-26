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

class w_partners_c_checker extends Controller
{
	public function index($settings = [])
	{
		return [
			'display_style'  => in_array($settings['display_style'], ['light', 'dark']) ? $settings['display_style'] : 'light',
			'display_number' => (int)$settings['display_number'],
			'display_height' => (int)$settings['display_height'] ?: '140',
			'id'             => $settings['id']
		];
	}

	public function column($settings = [])
	{
		return [
			'display_style' => in_array($settings['display_style'], ['light', 'dark']) ? $settings['display_style'] : 'light'
		];
	}
}

/*
NeoFrag Alpha 0.1.4
./widgets/partners/controllers/checker.php
*/