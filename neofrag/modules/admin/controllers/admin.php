<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_admin_c_admin extends Controller_Module
{
	public function index()
	{
		$users = $this
			->title($this->lang('dashboard'))
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
						return NeoFrag()->user->link($data['user_id'], $data['username']);
					},
				],
				[
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr($this->lang('date_time_long'), $data['registration_date']).'">'.time_span($data['registration_date']).'</span>';
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
							->heading($this->lang('news', $count = $this->db->select('COUNT(*)')->from('nf_news')->where('published', TRUE)->row()), 'fa-newspaper-o', 'admin/news')
							->body($count)
							->color('bg-aqua')
							->size('col-md-4 col-lg-2')
							->footer($this->lang('manage_news').' '.icon('fa-arrow-circle-right'))
				),
				$this->col(
					$this	->panel_box()
							->heading($this->lang('members', $count = $this->db->select('COUNT(*)')->from('nf_users')->where('deleted', FALSE)->row()), 'fa-users', 'admin/user')
							->body($count)
							->color('bg-green')
							->size('col-md-4 col-lg-2')
							->footer($this->lang('manage_members').' '.icon('fa-arrow-circle-right'))
				),
				$this->col(
					$this	->panel_box()
							->heading($this->lang('events', $count = $this->db->select('COUNT(*)')->from('nf_events')->where('published', TRUE)->row()), 'fa-calendar', 'admin/events')
							->body($count)
							->color('bg-blue')
							->size('col-md-4 col-lg-2')
							->footer($this->lang('manage_events').' '.icon('fa-arrow-circle-right'))
				),
				$this->col(
					$this	->panel_box()
							->heading($this->lang('teams', $count = $this->db->select('COUNT(*)')->from('nf_teams')->row()), 'fa-gamepad', 'admin/teams')
							->body($count)
							->color('bg-red')
							->size('col-md-4 col-lg-2')
							->footer($this->lang('manage_teams').' '.icon('fa-arrow-circle-right'))
				),
				$this->col(
					$this	->panel_box()
							->heading($this->lang('messages', $count = $this->db->select('COUNT(*)')->from('nf_forum_messages')->row()), 'fa-comments', 'admin/forum')
							->body($count)
							->color('bg-teal')
							->size('col-md-4 col-lg-2')
							->footer($this->lang('manage_forum').' '.icon('fa-arrow-circle-right'))
				),
				$this->col(
					$this	->panel_box()
							->heading($this->lang('comments', $count = $this->db->select('COUNT(*)')->from('nf_comments')->row()), 'fa-comments-o', 'admin/comments')
							->body($count)
							->color('bg-maroon')
							->size('col-md-4 col-lg-2')
							->footer($this->lang('manage_comments').' '.icon('fa-arrow-circle-right'))
				)
			),
			$this->row(
				$this	->col(
							$this	->panel_widget(1),
							$this	->panel()
									->heading('<a href="https://neofr.ag">'.$this->lang('nf_news').'</a>', 'fa-newspaper-o')
									->body($this->view('nf_news'))
									
						)
						->size('col-md-8'),
				$this	->col(
							$this	->panel()
									->heading($this->lang('connected_users'), 'fa-globe')
									->body($this->view('users_online', [
										'currently' => $this->db->select('COUNT(*)')->from('nf_sessions')->where('last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')->where('is_crawler', FALSE)->row(),
										'max'       => statistics('nf_sessions_max_simultaneous')
									]))
									->footer('<a href="'.url('admin/user/sessions').'">'.$this->lang('view_all_sessions').'</a>'),
							$this	->panel()
									->heading($this->lang('last_registrations'), 'fa-users')
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
			echo $help->method($method);
		}
		else
		{
			throw new Exception(NeoFrag::UNFOUND);
		}
	}

	public function about()
	{
		$this->title($this->lang('about'))->subtitle('NeoFrag CMS '.NEOFRAG_VERSION);

		return [
			$this->row(
				$this->col(
					$this	->panel()
							->heading($this->lang('lgpl_license'))
							->body($this->view('license'))
							->size('col-md-12 col-lg-8')
				),
				$this->col(
					$this	->panel()
							->heading($this->lang('the_team'))
							->body('	<div class="row">
											<div class="col-md-6 text-center">
												<p><img src="https://neofr.ag/images/team/foxley.jpg" class="img-circle" style="max-width: 100px;" alt="" /></p>
												<div><b>Michaël BILCOT "FoxLey"</b></div>
												<span class="text-muted">'.$this->lang('web_developer').'</span>
											</div>
											<div class="col-md-6 text-center">
												<p><img src="https://neofr.ag/images/team/eresnova.jpg" class="img-circle" style="max-width: 100px;" alt="" /></p>
												<div><b>Jérémy VALENTIN "eResnova"</b></div>
												<span class="text-muted">'.$this->lang('web_designer').'</span>
											</div>
										</div>')
							->size('col-md-12 col-lg-4')
				)
			)
		];
	}

	public function notifications()
	{
		$this	->title($this->lang('notifications'))
				->icon('fa-flag');
		
		return $this->panel()
					->heading($this->lang('notifications'), 'fa-flag')
					->body($this->lang('unavailable_feature'))
					->color('info')
					->size('col-md-12');
	}
	
	public function database()
	{
		$this	->title($this->lang('database'))
				->icon('fa-database');
		
		return $this->panel()
					->heading($this->lang('database'), 'fa-database')
					->body($this->lang('unavailable_feature'))
					->color('info')
					->size('col-md-12');
	}
}
