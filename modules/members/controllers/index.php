<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Members\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Index extends Controller_Module
{
	public function index($members)
	{
		return $this->table2($members, $this->lang('Il n\'y a pas encore de membre dans ce groupe'))
					->col('', 'col-1', 'avatar')
					->col(function($data){
						$socials = $this	->array([
							['website',   'fas fa-globe',       ''],
							['linkedin',  'fab fa-linkedin-in', 'https://www.linkedin.com/in/'],
							['github',    'fab fa-github',      'https://github.com/'],
							['instagram', 'fab fa-instagram',   'https://www.instagram.com/'],
							['twitch',    'fab fa-twitch',      'https://www.twitch.tv/']
						])
						->filter(function($a) use ($data){
							return $data->profile()->{$a[0]};
						})
						->each(function($a) use ($data){
							return '<a href="'.$a[2].$data->profile()->{$a[0]}.'" class="btn btn-light btn-sm" target="_blank">'.icon($a[1]).'</a>';
						});

						return '<div>'.$data->link().'</div>'.(!$socials->empty() ? '<div class="socials">'.$socials.'</div>' : '');
					})
					->col(function($data){
						return $this->user() && $this->user->id != $data->id ? $this->button()->icon('far fa-envelope')->url('user/messages/compose/'.$data->id.'/'.url_title($data->username))->compact()->outline() : '';
					})
					->panel();
	}

	public function _group($title, $members)
	{
		return $this->array
					->append($this->panel()->body('<h2 class="m-0">'.$this->lang('Groupe').' <small>'.$title.'</small>'.$this->button()->tooltip($this->lang('Voir tous les membres'))->icon('fas fa-times')->url('members')->color('danger pull-right')->compact()->outline().'</h2>'))
					->append($this->index($members));
	}
}
