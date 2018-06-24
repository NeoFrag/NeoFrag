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
		$this	->table
				->add_columns([
					[
						'content' => function($data){
							return NeoFrag()->user->avatar($data['avatar'], $data['sex'], $data['user_id'], $data['username']);
						},
						'size'    => TRUE
					],
					[
						'title'   => 'Membre',
						'content' => function($data){
							return '<div>'.NeoFrag()->user->link($data['user_id'], $data['username']).'</div><small>'.icon('fa-circle '.($data['online'] ? 'text-green' : 'text-gray')).' '.$this->lang($data['admin'] ? 'admin' : 'member').' '.$this->lang($data['online'] ? 'online' : 'offline').'</small>';
						},
						'search'  => function($data){
							return $data['username'];
						}
					],
					[
						'content' => function($data){
							return $this->user() && $this->user('user_id') != $data['user_id'] ? $this->button()->icon('fa-envelope-o')->url('user/messages/compose/'.$data['user_id'].'/'.url_title($data['username']))->compact()->outline() : '';
						},
						'size'    => TRUE,
						'align'   => 'right',
						'class'   => 'vcenter'
					]
				])
				->data($members)
				->no_data($this->lang('Il n\'y a pas encore de membre dans ce groupe'));

		return $this->panel()
					->heading()
					->body($this->table->display());
	}

	public function _group($title, $members)
	{
		return [
			$this->panel()->body('<h2 class="no-margin">'.$this->lang('Groupe').' <small>'.$title.'</small>'.$this->button()->tooltip($this->lang('Voir tous les membres'))->icon('fa-close')->url('members')->color('danger pull-right')->compact()->outline().'</h2>'),
			$this->index($members)
		];
	}
}
