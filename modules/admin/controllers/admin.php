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
		if (!$this->user->admin)
		{
			return '';
		}

		$users = $this
			->title($this->lang('Tableau de bord'))
			->js('jquery.knob')
			->js_load('$(\'.knob\').knob();')
			->table()
			->add_columns([
				[
					'content' => function($data){
						return '<a href="mailto:'.$data['email'].'" data-toggle="tooltip" title="'.$data['email'].'">'.icon('fas fa-envelope').'</a>';
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
							->heading($this->lang('Actualité|Actualités', $count = $this->db->from('nf_news')->where('published', TRUE)->count()), 'far fa-newspaper', 'admin/news')
							->body($count)
							->color('bg-aqua')
							->size('col-4 col-lg-2')
							->footer($this->lang('Voir la liste').' '.icon('fas fa-arrow-circle-right'))
				),
				$this->col(
					$this	->panel_box()
							->heading($this->lang('Membre|Membres', $count = $this->db->from('nf_user')->where('deleted', FALSE)->count()), 'fas fa-users', 'admin/user')
							->body($count)
							->color('bg-green')
							->size('col-4 col-lg-2')
							->footer($this->lang('Gérer les utilisateurs').' '.icon('fas fa-arrow-circle-right'))
				),
				$this->col(
					$this	->panel_box()
							->heading($this->lang('Événement|Événements', $count = $this->db->from('nf_events')->where('published', TRUE)->count()), 'fas fa-calendar-alt', 'admin/events')
							->body($count)
							->color('bg-blue')
							->size('col-4 col-lg-2')
							->footer($this->lang('Gérer le calendrier').' '.icon('fas fa-arrow-circle-right'))
				),
				$this->col(
					$this	->panel_box()
							->heading($this->lang('Équipe|Équipes', $count = $this->db->from('nf_teams')->count()), 'fas fa-headset', 'admin/teams')
							->body($count)
							->color('bg-red')
							->size('col-4 col-lg-2')
							->footer($this->lang('Gérer les équipes').' '.icon('fas fa-arrow-circle-right'))
				),
				$this->col(
					$this	->panel_box()
							->heading($this->lang('Message|Messages', $count = $this->db->from('nf_forum_messages')->count()), 'fas fa-comments', 'admin/forum')
							->body($count)
							->color('bg-teal')
							->size('col-4 col-lg-2')
							->footer($this->lang('Gérer le forum').' '.icon('fas fa-arrow-circle-right'))
				),
				$this->col(
					$this	->panel_box()
							->heading($this->lang('Commentaire|Commentaires', $count = $this->db->from('nf_comment')->count()), 'far fa-comments', 'admin/comments')
							->body($count)
							->color('bg-maroon')
							->size('col-4 col-lg-2')
							->footer($this->lang('Gérer les commentaires').' '.icon('fas fa-arrow-circle-right'))
				)
			),
			$this->row(
				$this->col('<div class="widget widget-talks">'.$this->widget('talks')->output('index', ['talk_id' => 1]).'</div>')->size('col-8'),
				$this	->col(
							$this	->panel()
									->heading($this->lang('Utilisateurs connectés'), 'fas fa-globe')
									->body($this->view('users_online', [
										'currently' => $this->db->from('nf_session')->where('last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')->count(),
										'max'       => statistics('nf_sessions_max_simultaneous')
									]))
									->footer('<a href="'.url('admin/user/sessions').'">'.$this->lang('Voir toutes les sessions actives').'</a>'),
							$this	->panel()
									->heading($this->lang('Dernières inscriptions'), 'fas fa-users')
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
