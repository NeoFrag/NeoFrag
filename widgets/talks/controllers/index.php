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

class w_talks_c_index extends Controller_Widget
{
	public function index($settings = array())
	{
		$this	->js('talks')
				->css('talks')
				->js('jquery.mCustomScrollbar.min')
				->css('jquery.mCustomScrollbar.min');
		
		$params = array(
			'content' => '<div data-talk-id="'.$settings['talk_id'].'">'.$this->load->view('index', array(
				'messages' => $this->model()->get_messages($settings['talk_id'])
			)).'</div>'
		);
		
		if (is_authorized('talks', 'write', $settings['talk_id']))
		{
			$params['footer'] = '	<form>
										<div class="input-group">
											<input type="text" class="form-control" placeholder="Votre message..." />
											<span class="input-group-btn">
												<button class="btn btn-primary" type="submit"><i class="fa fa-check"></i></button>
											</span>
										</div>
									</form>';
		}
		
		return new Panel($params);
	}
}

/*
NeoFrag Alpha 0.1
./widgets/talks/controllers/index.php
*/