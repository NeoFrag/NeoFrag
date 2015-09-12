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

class m_admin_c_admin extends Controller_Module
{
	public function index()
	{
		$users = $this
			->title('Tableau de bord')
			->js('jquery.knob')
			->js_load('$(\'.knob\').knob();')
			->load->library('table')
			->add_columns(array(
				array(
					'content' => function($data){
						return '<a href="mailto:'.$data['email'].'" data-toggle="tooltip" title="'.$data['email'].'">'.icon('fa-envelope').'</a>';
					},
				),
				array(
					'content' => function($data){
						return NeoFrag::loader()->user->link($data['user_id'], $data['username']);
					},
				),
				array(
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag::loader()->lang('date_time_long'), $data['registration_date']).'">'.time_span($data['registration_date']).'</span>';
					},
					'class'   => 'text-right',
				)
			))
			->data($this->db->from('nf_users')->where('deleted', FALSE)->order_by('user_id DESC')->limit(5)->get())
			->display();
		
		return array(
			new Row(
				new Col(
					new Panel_box(array(
						'label'  => 'Actualités',
						'icon'   => 'fa-newspaper-o',
						'color'  => 'bg-aqua',
						'count'  => $this->db->select('COUNT(*)')->from('nf_news')->where('published', TRUE)->row(),
						'url'    => 'admin/news.html',
						'footer' => 'Voir la liste '.icon('fa-arrow-circle-right'),
						'size'   => 'col-md-4 col-lg-2'
					))
				),
				new Col(
					new Panel_box(array(
						'label'  => 'Membres',
						'icon'   => 'fa-users',
						'color'  => 'bg-green',
						'count'  => $this->db->select('COUNT(*)')->from('nf_users')->where('deleted', FALSE)->row(),
						'url'    => 'admin/members.html',
						'footer' => 'Gérer les utilisateurs '.icon('fa-arrow-circle-right'),
						'size'   => 'col-md-4 col-lg-2'
					))
				),
				new Col(
					new Panel_box(array(
						'label'  => 'Événements',
						'icon'   => 'fa-calendar',
						'color'  => 'bg-blue',
						'count'  => 0,//TODO
						'url'    => 'admin/events.html',
						'footer' => 'Gérer le calendrier '.icon('fa-arrow-circle-right'),
						'size'   => 'col-md-4 col-lg-2'
					))
				),
				new Col(
					new Panel_box(array(
						'label'  => 'Équipes',
						'icon'   => 'fa-gamepad',
						'color'  => 'bg-red',
						'count'  => $this->db->select('COUNT(*)')->from('nf_teams')->row(),
						'url'    => 'admin/teams.html',
						'footer' => 'Gérer les équipes '.icon('fa-arrow-circle-right'),
						'size'   => 'col-md-4 col-lg-2'
					))
				),
				new Col(
					new Panel_box(array(
						'label'  => 'Messages',
						'icon'   => 'fa-comments',
						'color'  => 'bg-teal',
						'count'  => $this->db->select('COUNT(*)')->from('nf_forum_messages')->row(),
						'url'    => 'admin/forum.html',
						'footer' => 'Gérer le forum '.icon('fa-arrow-circle-right'),
						'size'   => 'col-md-4 col-lg-2'
					))
				),
				new Col(
					new Panel_box(array(
						'label'  => 'Commentaires',
						'icon'   => 'fa-comments-o',
						'color'  => 'bg-maroon',
						'count'  => $this->db->select('COUNT(*)')->from('nf_comments')->row(),
						'url'    => 'admin/comments.html',
						'footer' => 'Gérer les commentaires '.icon('fa-arrow-circle-right'),
						'size'   => 'col-md-4 col-lg-2'
					))
				)
			),
			new Row(
				new Col(
					new Widget_view(array('widget_id' => 1)),
					new Panel(array(
						'title'   => '<a href="//www.neofrag.com">Actualité NeoFrag CMS</a>',
						'icon'    => 'fa-newspaper-o',
						'content' => $this->load->view('nf_news'),
						'size'    => 'col-md-8'
					))
				),
				new Col(
					new Panel(array(
						'title'   => 'Utilisateurs connectés',
						'icon'    => 'fa-globe',
						'content' => $this->load->view('users_online', array(
							'currently' => $this->db->select('COUNT(*)')->from('nf_sessions')->where('last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')->where('is_crawler', FALSE)->row(),
							'max'       => statistics('nf_sessions_max_simultaneous')
						)),
						'footer' => '<a href="'.url('admin/members/sessions.html').'">Voir toutes les sessions actives</a>',
						'size'    => 'col-md-4'
					)),
					new Panel(array(
						'title'   => 'Dernières inscriptions',
						'icon'    => 'fa-users',
						'content' => $users,
						'size'    => 'col-md-4'
					))
				)
			)
		);
	}

	public function help($module_name, $method)
	{
		$this->ajax();

		if (($module = $this->load->module($module_name)) && ($help = $module->load->controller('admin_help')) && method_exists($help, $method))
		{
			echo $this->template->parse($help->method($method), array(), $module->load);
		}
		else
		{
			throw new Exception(NeoFrag::UNFOUND);
		}
	}

	public function about()
	{
		$this->title('À propos')->subtitle('NeoFrag CMS '.NEOFRAG_VERSION);

		return array(
			new Row(
				new Col(
					new Panel(array(
						'title'   => 'Licence LGPL v3',
						'content' => $this->load->view('license'),
						'size'    => 'col-md-12 col-lg-8'
					))
				),
				new Col(
					new Panel(array(
						'title'   => 'L\'équipe',
						'content' => '	<div class="row">
											<div class="col-md-6 text-center">
												<p><img src="//www.neofrag.com/images/team/foxley.jpg" class="img-circle" style="max-width: 100px;" alt="" /></p>
												<div><b>Michaël BILCOT "FoxLey"</b></div>
												<span class="text-muted">Développeur web</span>
											</div>
											<div class="col-md-6 text-center">
												<p><img src="//www.neofrag.com/images/team/eresnova.jpg" class="img-circle" style="max-width: 100px;" alt="" /></p>
												<div><b>Jérémy VALENTIN "eResnova"</b></div>
												<span class="text-muted">Web designer</span>
											</div>
										</div>',
						'size'    => 'col-md-12 col-lg-4'
					))
				)
			)
		);
	}

	public function phpinfo()
	{
		$this	->title('Serveur')
				->subtitle('PHP '.phpversion())
				->css('phpinfo');
		
		$extentions = get_loaded_extensions();
		natcasesort($extentions);

		ob_start();
		phpinfo();

		$output = array(new Panel(array(
			'content' => $this->load->view('phpinfo', array(
				'extentions' => $extentions
			))
		)));
		
		if (preg_match_all('#(?:<h2>(.*?)</h2>.*?)?<table.*?>(.*?)</table>#s', ob_get_clean(), $matches, PREG_SET_ORDER))
		{
			foreach (array_offset_left($matches) as $match)
			{
				$output[] = new Panel(array(
					'title'   => $match[1],
					'content' => '<table class="table table-hover table-striped">'.$match[2].'</table>'
				));
			}
		}
		
		return $output;
	}
	
	public function notifications()
	{
		$this	->title('Notifications')
				->icon('fa-flag');
		
		return new Panel(array(
			'title'   => 'Notifications',
			'icon'    => 'fa-flag',
			'style'   => 'panel-info',
			'content' => 'Cette fonctionnalité n\'est pas disponible pour l\'instant.',
			'size'    => 'col-md-12'
		));
	}
	
	public function database()
	{
		$this	->title('Base de données')
				->icon('fa-database');
		
		return new Panel(array(
			'title'   => 'Base de données',
			'icon'    => 'fa-database',
			'style'   => 'panel-info',
			'content' => 'Cette fonctionnalité n\'est pas disponible pour l\'instant.',
			'size'    => 'col-md-12'
		));
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/modules/admin/controllers/admin.php
*/