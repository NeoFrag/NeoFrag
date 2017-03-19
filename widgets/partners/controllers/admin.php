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

class w_partners_c_admin extends Controller
{
	public function index($settings = [])
	{
		if (empty($settings['id']))
		{
			$settings['id'] = unique_id();
		}

		return $this->view('admin_index', [
			'display_style'  => $settings['display_style'],
			'display_number' => $settings['display_number'],
			'display_height' => $settings['display_height'],
			'id'             => $settings['id']
		]);
	}

	public function column($settings = [])
	{
		return $this->view('admin_column', [
			'display_style'  => $settings['display_style']
		]);
	}
}

/*
NeoFrag Alpha 0.1.5
./widgets/partners/controllers/admin.php
*/