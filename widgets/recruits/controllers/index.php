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

class w_recruits_c_index extends Controller_Widget
{
	public function index($settings = [])
	{
		$recruits = $this->model()->get_last_recruits();

		if (!empty($recruits))
		{
			return $this->panel()
						->heading('Offres de recrutement')
						->body($this->view('index', [
							'recruits' => $recruits
						]), FALSE)
						->footer('<a href="'.url('recruits').'">'.icon('fa-arrow-circle-o-right').' Voir toutes les annonces</a>');
		}
		else
		{
			return $this->panel()
						->heading('Recrutement')
						->body('Aucune offre pour le moment...');
		}
	}

	public function recruit($settings = [])
	{
		$recruit = $this->model()->get_recruit($settings['recruit_id']);

		if (!empty($recruit))
		{
			if (!$recruit['closed'] && ($recruit['candidacies_accepted'] < $recruit['size']) && (!$recruit['date_end'] || strtotime($recruit['date_end']) > time()))
			{
				return $this->panel()
							->heading('Recrutement')
							->body($this->view('recruit', [
								'recruit_id'   => $recruit['recruit_id'],
								'title'        => $recruit['title'],
								'introduction' => bbcode(str_shortener($recruit['introduction'], 190)),
								'date'         => $recruit['date'],
								'size'         => $recruit['size'] - $recruit['candidacies_accepted'],
								'role'         => $recruit['role'],
								'icon'         => $recruit['icon'],
								'date_end'     => $recruit['date_end'],
								'team_id'      => $recruit['team_id'],
								'team_name'    => $recruit['team_name'],
								'image_id'     => $recruit['image_id']
							]), FALSE)
							->footer('<a href="'.url('recruits/'.$recruit['recruit_id'].'/'.url_title($recruit['title'])).'">'.icon('fa-eye').' Découvrir l\'offre</a>');
			}
		}
		else
		{
			return $this->panel()
						->heading('Recrutement')
						->body('Aucune offre pour le moment');
		}
	}
}

/*
NeoFrag Alpha 0.1.6
./widgets/recruits/controllers/index.php
*/