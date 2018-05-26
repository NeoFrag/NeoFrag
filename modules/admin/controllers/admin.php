<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Admin\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin extends Controller_Module
{
	public function index()
	{
		$users = $this
			->title($this->lang('Tableau de bord'))
			->js('jquery.knob')
			->js_load('$(\'.knob\').knob();')
			->table()
			->add_columns([
				[
					'content' => function($data){
						return '<a href="mailto:'.$data['email'].'" data-toggle="tooltip" title="'.$data['email'].'">'.icon('fa-envelope').'</a>';
					}
				],
				[
					'content' => function($data){
						return NeoFrag()->user->link($data['id'], $data['username']);
					}
				],
				[
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr($this->lang('%A %e %B %Y, %H:%M'), $data['registration_date']).'">'.time_span($data['registration_date']).'</span>';
					},
					'class'   => 'text-right'
				]
			])
			->data($this->db->from('nf_user')->where('deleted', FALSE)->order_by('id DESC')->limit(5)->get())
			->display();

		return $this->array(
			$this->row(
				$this->col(
					$this	->panel_box()
							->heading($this->lang('Actualité|Actualités', $count = $this->db->select('COUNT(*)')->from('nf_news')->where('published', TRUE)->row()), 'fa-newspaper-o', 'admin/news')
							->body($count)
							->color('bg-aqua')
							->size('col-4 col-lg-2')
							->footer($this->lang('Voir la liste').' '.icon('fa-arrow-circle-right'))
				),
				$this->col(
					$this	->panel_box()
							->heading($this->lang('Membre|Membres', $count = $this->db->select('COUNT(*)')->from('nf_user')->where('deleted', FALSE)->row()), 'fa-users', 'admin/user')
							->body($count)
							->color('bg-green')
							->size('col-4 col-lg-2')
							->footer($this->lang('Gérer les utilisateurs').' '.icon('fa-arrow-circle-right'))
				),
				$this->col(
					$this	->panel_box()
							->heading($this->lang('Événement|Événements', $count = $this->db->select('COUNT(*)')->from('nf_events')->where('published', TRUE)->row()), 'fa-calendar', 'admin/events')
							->body($count)
							->color('bg-blue')
							->size('col-4 col-lg-2')
							->footer($this->lang('Gérer le calendrier').' '.icon('fa-arrow-circle-right'))
				),
				$this->col(
					$this	->panel_box()
							->heading($this->lang('Équipe|Équipes', $count = $this->db->select('COUNT(*)')->from('nf_teams')->row()), 'fa-gamepad', 'admin/teams')
							->body($count)
							->color('bg-red')
							->size('col-4 col-lg-2')
							->footer($this->lang('Gérer les équipes').' '.icon('fa-arrow-circle-right'))
				),
				$this->col(
					$this	->panel_box()
							->heading($this->lang('Message|Messages', $count = $this->db->select('COUNT(*)')->from('nf_forum_messages')->row()), 'fa-comments', 'admin/forum')
							->body($count)
							->color('bg-teal')
							->size('col-4 col-lg-2')
							->footer($this->lang('Gérer le forum').' '.icon('fa-arrow-circle-right'))
				),
				$this->col(
					$this	->panel_box()
							->heading($this->lang('Commentaire|Commentaires', $count = $this->db->select('COUNT(*)')->from('nf_comment')->row()), 'fa-comments-o', 'admin/comments')
							->body($count)
							->color('bg-maroon')
							->size('col-4 col-lg-2')
							->footer($this->lang('Gérer les commentaires').' '.icon('fa-arrow-circle-right'))
				)
			),
			$this->row(
				$this->col($this->widget('talks')->output('index', ['talk_id' => 1]))->size('col-8'),
				$this	->col(
							$this	->panel()
									->heading($this->lang('Utilisateurs connectés'), 'fa-globe')
									->body($this->view('users_online', [
										'currently' => $this->db->select('COUNT(*)')->from('nf_session')->where('last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')->row(),
										'max'       => statistics('nf_sessions_max_simultaneous')
									]))
									->footer('<a href="'.url('admin/user/sessions').'">'.$this->lang('Voir toutes les sessions actives').'</a>'),
							$this	->panel()
									->heading($this->lang('Dernières inscriptions'), 'fa-users')
									->body($users)
						)
						->size('col-4')
			)
		);
	}

	public function help($module_name, $method)
	{
		if (($module = $this->module($module_name)) && ($help = @$module->controller('admin_help')) && $help->has_method($method))
		{
			$this->ajax();
			return call_user_func_array([$help, $method]);
		}
	}
}
