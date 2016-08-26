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

class w_html_c_admin extends Controller_Widget
{
	public function index($settings = [])
	{
		return $this->load->view('bbcode', $settings);
	}
	
	public function html($settings = [])
	{
		return '<textarea class="form-control" name="settings[content]" placeholder="'.$this('html_code').'" rows="6">'.$settings['content'].'</textarea>';
	}
}

/*
NeoFrag Alpha 0.1.3
./neofrag/widgets/html/controllers/admin.php
*/