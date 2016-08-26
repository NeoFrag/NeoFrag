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

class m_admin_c_admin extends Controller_Module
{
	public $administrable = FALSE;

	public function index()
	{
		$users = $this
			->title($this('dashboard'))
			->js('jquery.knob')
			->js_load('$(\'.knob\').knob();')
			->table
			->add_columns([
				[
					'content' => function($data){
						return '<a href="mailto:'.$data['email'].'" data-toggle="tooltip" title="'.$data['email'].'">'.icon('fa-envelope').'</a>';
					},
				],
				[
					'content' => function($data){
						return NeoFrag::loader()->user->link($data['user_id'], $data['username']);
					},
				],
				[
					'content' => function($data, $loader){
						return '<span data-toggle="tooltip" title="'.timetostr($loader->lang('date_time_long'), $data['registration_date']).'">'.time_span($data['registration_date']).'</span>';
					},
					'class'   => 'text-right',
				]
			])
			->data($this->db->from('nf_users')->where('deleted', FALSE)->order_by('user_id DESC')->limit(5)->get())
			->display();
		
		return [
			new Row(
				new Col(
					new Panel_box([
						'label'  => $this('news', $count = $this->db->select('COUNT(*)')->from('nf_news')->where('published', TRUE)->row()),
						'icon'   => 'fa-newspaper-o',
						'color'  => 'bg-aqua',
						'count'  => $count,
						'url'    => 'admin/news.html',
						'footer' => $this('manage_news').' '.icon('fa-arrow-circle-right'),
						'size'   => 'col-md-4 col-lg-2'
					])
				),
				new Col(
					new Panel_box([
						'label'  => $this('members', $count = $this->db->select('COUNT(*)')->from('nf_users')->where('deleted', FALSE)->row()),
						'icon'   => 'fa-users',
						'color'  => 'bg-green',
						'count'  => $count,
						'url'    => 'admin/members.html',
						'footer' => $this('manage_members').' '.icon('fa-arrow-circle-right'),
						'size'   => 'col-md-4 col-lg-2'
					])
				),
				new Col(
					new Panel_box([
						'label'  => $this('events', $count = 0),//TODO
						'icon'   => 'fa-calendar',
						'color'  => 'bg-blue',
						'count'  => $count,
						'url'    => 'admin/events.html',
						'footer' => $this('manage_events').' '.icon('fa-arrow-circle-right'),
						'size'   => 'col-md-4 col-lg-2'
					])
				),
				new Col(
					new Panel_box([
						'label'  => $this('teams', $count = $this->db->select('COUNT(*)')->from('nf_teams')->row()),
						'icon'   => 'fa-gamepad',
						'color'  => 'bg-red',
						'count'  => $count,
						'url'    => 'admin/teams.html',
						'footer' => $this('manage_teams').' '.icon('fa-arrow-circle-right'),
						'size'   => 'col-md-4 col-lg-2'
					])
				),
				new Col(
					new Panel_box([
						'label'  => $this('messages', $count = $this->db->select('COUNT(*)')->from('nf_forum_messages')->row()),
						'icon'   => 'fa-comments',
						'color'  => 'bg-teal',
						'count'  => $count,
						'url'    => 'admin/forum.html',
						'footer' => $this('manage_forum').' '.icon('fa-arrow-circle-right'),
						'size'   => 'col-md-4 col-lg-2'
					])
				),
				new Col(
					new Panel_box([
						'label'  => $this('comments', $count = $this->db->select('COUNT(*)')->from('nf_comments')->row()),
						'icon'   => 'fa-comments-o',
						'color'  => 'bg-maroon',
						'count'  => $count,
						'url'    => 'admin/comments.html',
						'footer' => $this('manage_comments').' '.icon('fa-arrow-circle-right'),
						'size'   => 'col-md-4 col-lg-2'
					])
				)
			),
			new Row(
				new Col(
					new Widget_view(['widget_id' => 1]),
					new Panel([
						'title'   => '<a href="https://neofr.ag">'.$this('nf_news').'</a>',
						'icon'    => 'fa-newspaper-o',
						'content' => $this->load->view('nf_news'),
						'size'    => 'col-md-8'
					])
				),
				new Col(
					new Panel([
						'title'   => $this('connected_users'),
						'icon'    => 'fa-globe',
						'content' => $this->load->view('users_online', [
							'currently' => $this->db->select('COUNT(*)')->from('nf_sessions')->where('last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')->where('is_crawler', FALSE)->row(),
							'max'       => statistics('nf_sessions_max_simultaneous')
						]),
						'footer' => '<a href="'.url('admin/members/sessions.html').'">'.$this('view_all_sessions').'</a>',
						'size'    => 'col-md-4'
					]),
					new Panel([
						'title'   => $this('last_registrations'),
						'icon'    => 'fa-users',
						'content' => $users,
						'size'    => 'col-md-4'
					])
				)
			)
		];
	}

	public function help($module_name, $method)
	{
		$this->ajax();

		if (($module = $this->load->module($module_name)) && ($help = $module->load->controller('admin_help')) && method_exists($help, $method))
		{
			echo $this->template->parse($help->method($method), [], $module->load);
		}
		else
		{
			throw new Exception(NeoFrag::UNFOUND);
		}
	}

	public function about()
	{
		$this->title($this('about'))->subtitle('NeoFrag CMS '.NEOFRAG_VERSION);

		return [
			new Row(
				new Col(
					new Panel([
						'title'   => $this('lgpl_license'),
						'content' => $this->load->view('license'),
						'size'    => 'col-md-12 col-lg-8'
					])
				),
				new Col(
					new Panel([
						'title'   => $this('the_team'),
						'content' => '	<div class="row">
											<div class="col-md-6 text-center">
												<p><img src="https://neofr.ag/images/team/foxley.jpg" class="img-circle" style="max-width: 100px;" alt="" /></p>
												<div><b>Michaël BILCOT "FoxLey"</b></div>
												<span class="text-muted">'.$this('web_developer').'</span>
											</div>
											<div class="col-md-6 text-center">
												<p><img src="https://neofr.ag/images/team/eresnova.jpg" class="img-circle" style="max-width: 100px;" alt="" /></p>
												<div><b>Jérémy VALENTIN "eResnova"</b></div>
												<span class="text-muted">'.$this('web_designer').'</span>
											</div>
										</div>',
						'size'    => 'col-md-12 col-lg-4'
					])
				)
			)
		];
	}

	public function phpinfo()
	{
		$this	->title($this('server'))
				->subtitle('PHP '.phpversion())
				->css('phpinfo');
		
		$extensions = get_loaded_extensions();
		natcasesort($extensions);

		ob_start();
		phpinfo();

		$output = [new Panel([
			'content' => $this->load->view('phpinfo', [
				'extensions' => $extensions
			])
		])];
		
		if (preg_match_all('#(?:<h2>(.*?)</h2>.*?)?<table.*?>(.*?)</table>#s', ob_get_clean(), $matches, PREG_SET_ORDER))
		{
			foreach (array_offset_left($matches) as $match)
			{
				$output[] = new Panel([
					'title'   => $match[1],
					'content' => '<table class="table table-hover table-striped">'.$match[2].'</table>'
				]);
			}
		}
		
		return $output;
	}
	
	public function notifications()
	{
		$this	->title($this('notifications'))
				->icon('fa-flag');
		
		return new Panel([
			'title'   => $this('notifications'),
			'icon'    => 'fa-flag',
			'style'   => 'panel-info',
			'content' => $this('unavailable_feature'),
			'size'    => 'col-md-12'
		]);
	}
	
	public function database()
	{
		$this	->title($this('database'))
				->icon('fa-database');
		
		return new Panel([
			'title'   => $this('database'),
			'icon'    => 'fa-database',
			'style'   => 'panel-info',
			'content' => $this('unavailable_feature'),
			'size'    => 'col-md-12'
		]);
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/modules/admin/controllers/admin.php
*/