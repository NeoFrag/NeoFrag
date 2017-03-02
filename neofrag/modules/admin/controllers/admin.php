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
			$this->row(
				$this->col(
					$this	->panel_box()
							->heading($this('news', $count = $this->db->select('COUNT(*)')->from('nf_news')->where('published', TRUE)->row()), 'fa-newspaper-o', 'admin/news.html')
							->body($count)
							->color('bg-aqua')
							->size('col-md-4 col-lg-2')
							->footer($this('manage_news').' '.icon('fa-arrow-circle-right'))
				),
				$this->col(
					$this	->panel_box()
							->heading($this('members', $count = $this->db->select('COUNT(*)')->from('nf_users')->where('deleted', FALSE)->row()), 'fa-users', 'admin/user.html')
							->body($count)
							->color('bg-green')
							->size('col-md-4 col-lg-2')
							->footer($this('manage_members').' '.icon('fa-arrow-circle-right'))
				),
				$this->col(
					$this	->panel_box()
							->heading($this('events', $count = 0), 'fa-calendar', 'admin/events.html')//TODO
							->body($count)
							->color('bg-blue')
							->size('col-md-4 col-lg-2')
							->footer($this('manage_events').' '.icon('fa-arrow-circle-right'))
				),
				$this->col(
					$this	->panel_box()
							->heading($this('teams', $count = $this->db->select('COUNT(*)')->from('nf_teams')->row()), 'fa-gamepad', 'admin/teams.html')
							->body($count)
							->color('bg-red')
							->size('col-md-4 col-lg-2')
							->footer($this('manage_teams').' '.icon('fa-arrow-circle-right'))
				),
				$this->col(
					$this	->panel_box()
							->heading($this('messages', $count = $this->db->select('COUNT(*)')->from('nf_forum_messages')->row()), 'fa-comments', 'admin/forum.html')
							->body($count)
							->color('bg-teal')
							->size('col-md-4 col-lg-2')
							->footer($this('manage_forum').' '.icon('fa-arrow-circle-right'))
				),
				$this->col(
					$this	->panel_box()
							->heading($this('comments', $count = $this->db->select('COUNT(*)')->from('nf_comments')->row()), 'fa-comments-o', 'admin/comments.html')
							->body($count)
							->color('bg-maroon')
							->size('col-md-4 col-lg-2')
							->footer($this('manage_comments').' '.icon('fa-arrow-circle-right'))
				)
			),
			$this->row(
				$this	->col(
							$this	->panel_widget(1),
							$this	->panel()
									->heading('<a href="https://neofr.ag">'.$this('nf_news').'</a>', 'fa-newspaper-o')
									->body($this->view('nf_news'))
									
						)
						->size('col-md-8'),
				$this	->col(
							$this	->panel()
									->heading($this('connected_users'), 'fa-globe')
									->body($this->view('users_online', [
										'currently' => $this->db->select('COUNT(*)')->from('nf_sessions')->where('last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')->where('is_crawler', FALSE)->row(),
										'max'       => statistics('nf_sessions_max_simultaneous')
									]))
									->footer('<a href="'.url('admin/user/sessions.html').'">'.$this('view_all_sessions').'</a>'),
							$this	->panel()
									->heading($this('last_registrations'), 'fa-users')
									->body($users)
						)
						->size('col-md-4')
			)
		];
	}

	public function help($module_name, $method)
	{
		$this->ajax();

		if (($module = $this->module($module_name)) && ($help = $module->controller('admin_help')) && $help->has_method($method))
		{
			echo $this->output->parse($help->method($method), [], $module->load);
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
			$this->row(
				$this->col(
					$this	->panel()
							->heading($this('lgpl_license'))
							->body($this->view('license'))
							->size('col-md-12 col-lg-8')
				),
				$this->col(
					$this	->panel()
							->heading($this('the_team'))
							->body('	<div class="row">
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
										</div>')
							->size('col-md-12 col-lg-4')
				)
			)
		];
	}

	public function notifications()
	{
		$this	->title($this('notifications'))
				->icon('fa-flag');
		
		return $this->panel()
					->heading($this('notifications'), 'fa-flag')
					->body($this('unavailable_feature'))
					->color('info')
					->size('col-md-12');
	}
	
	public function database()
	{
		$this	->title($this('database'))
				->icon('fa-database');
		
		return $this->panel()
					->heading($this('database'), 'fa-database')
					->body($this('unavailable_feature'))
					->color('info')
					->size('col-md-12');
	}
}

/*
NeoFrag Alpha 0.1.5.2
./neofrag/modules/admin/controllers/admin.php
*/