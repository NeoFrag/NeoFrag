<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Recruits\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget as Controller_Widget;

class Index extends Controller_Widget
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
						->footer('<a href="'.url('recruits').'">'.icon('far fa-arrow-alt-circle-right').' Voir toutes les annonces</a>');
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
							->footer('<a href="'.url('recruits/'.$recruit['recruit_id'].'/'.url_title($recruit['title'])).'">'.icon('far fa-eye').' Découvrir l\'offre</a>');
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
