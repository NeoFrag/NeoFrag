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
					->col('', 'avatar')
					->col(function($data){
						return '<div>'.$data->link().'</div><small>'.icon('fa-circle '.($data->is_online() ? 'text-green' : 'text-gray')).' '.$this->lang($data->admin ? 'admin' : 'member').' '.$this->lang($data->is_online() ? 'online' : 'offline').'</small>';
					})
					->col(function($data){
						return $this->user->id && $this->user->id != $data->id ? $this->button()->icon('fa-envelope-o')->url('user/messages/compose/'.$data->id.'/'.url_title($data->username))->compact()->outline() : '';
					})
					->panel();
	}

	public function _group($title, $members)
	{
		return [
			$this->panel()->body('<h2 class="no-margin">'.$this->lang('Groupe').' <small>'.$title.'</small>'.$this->button()->tooltip($this->lang('Voir tous les membres'))->icon('fa-close')->url('members')->color('danger pull-right')->compact()->outline().'</h2>'),
			$this->index($members)
		];
	}
}
