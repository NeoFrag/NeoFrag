<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Teams\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget as Controller_Widget;

class Index extends Controller_Widget
{
	public function index($settings = [])
	{
		$this->css('teams');

		$teams = array_filter($this->module('teams')->model()->get_teams(), function($a){
			return $a['image_id'];
		});

		if (!empty($teams))
		{
			return $this->panel()
						->heading($this->lang('Nos équipes'))
						->body($this->view('index', [
							'teams'    => $teams
						]), FALSE)
						->footer('<a href="'.url('teams').'">'.icon('far fa-arrow-alt-circle-right').' '.$this->lang('Voir toutes nos équipes').'</a>', 'right');
		}
		else
		{
			return $this->panel()
						->heading($this->lang('Nos équipes'))
						->body($this->lang('Aucune équipe pour le moment'));
		}
	}
}
