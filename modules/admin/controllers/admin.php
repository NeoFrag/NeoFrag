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
				$this	->col(
							$this	->widget(1),
							$this	->panel()
									->heading('<a href="https://neofr.ag">'.$this->lang('Actualité NeoFrag CMS').'</a>', 'fa-newspaper-o')
									->body($this->view('nf_news'))

						)
						->size('col-8'),
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
		$this->ajax();

		if (($module = $this->module($module_name)) && ($help = $module->controller('admin_help')) && $help->has_method($method))
		{
			return call_user_func_array([$help, $method]);
		}
	}

	public function about()
	{
		$this->title($this->lang('À propos'))->subtitle('NeoFrag CMS '.NEOFRAG_VERSION);

		return $this->row(
			$this->col(
				$this	->panel()
						->heading($this->lang('Licence LGPL v3'))
						->body($this->view('license'))
						->size('col-12 col-lg-8')
			),
			$this->col(
				$this	->panel()
						->heading($this->lang('L\'équipe'))
						->body('	<div class="row">
										<div class="col-6 text-center">
											<p><img src="https://neofr.ag/images/team/foxley.jpg" class="rounded-circle" style="max-width: 100px;" alt="" /></p>
											<div><b>Michaël BILCOT "FoxLey"</b></div>
											<span class="text-muted">'.$this->lang('Développeur web').'</span>
										</div>
										<div class="col-6 text-center">
											<p><img src="https://neofr.ag/images/team/eresnova.jpg" class="rounded-circle" style="max-width: 100px;" alt="" /></p>
											<div><b>Jérémy VALENTIN "eResnova"</b></div>
											<span class="text-muted">'.$this->lang('Web designer').'</span>
										</div>
									</div>')
						->size('col-12 col-lg-4')
			)
		);
	}

	public function notifications()
	{
		$this	->title($this->lang('Notifications'))
				->icon('fa-flag');

		return $this->panel()
					->heading($this->lang('Notifications'), 'fa-flag')
					->body($this->lang('Cette fonctionnalité n\'est pas disponible pour l\'instant'))
					->color('info')
					->size('col-12');
	}

	public function database()
	{
		$this	->title($this->lang('Base de données'))
				->icon('fa-database');

		return $this->panel()
					->heading($this->lang('Base de données'), 'fa-database')
					->body($this->lang('Cette fonctionnalité n\'est pas disponible pour l\'instant'))
					->color('info')
					->size('col-12');
	}
}
