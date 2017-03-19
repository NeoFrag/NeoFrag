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

class m_comments_c_admin extends Controller_Module
{
	public function index($comments, $modules, $tab)
	{
		$this->tab->add_tab('', $this->lang('all_comments'), function() use ($comments){
			return $this->_tab_index($comments);
		});

		foreach ($modules as $module_name => $module)
		{
			list($title, $icon) = $module;
			$this->tab->add_tab($module_name, icon($icon).' '.$title, function() use ($comments, $title){
				return $this->_tab_index($comments, $title);
			});
		}
								
		return $this->panel()->body($this->tab->display($tab));
	}
	
	private function _tab_index($comments, $title = NULL)
	{
		$this->subtitle($title === NULL ? $this->lang('all_comments') : $title);
		
		if ($title === NULL)
		{
			$this->table->add_columns([
				[
					'title'   => $this->lang('module'),
					'content' => function($data){
						return '<a href="'.url('admin/comments/'.$data['module'].'.html').'">'.icon($data['icon']).' '.$data['module_title'].'</a>';
					},
					'size'    => '25%',
					'sort'    => function($data){
						return $data['module_title'];
					},
					'search'  => function($data){
						return $data['module_title'];
					}
				]
			]);
		}
	
		return $this->table->add_columns([
			[
				'title'   => $this->lang('name'),
				'content' => function($data){
					return $data['title'];
				},
				'sort'    => function($data){
					return $data['title'];
				},
				'search'  => function($data){
					return $data['title'];
				}
			],
			[
				'title'   => '<i class="fa fa-comments-o" data-toggle="tooltip" title="'.$this->lang('number_comments').'"></i>',
				'content' => function($data){
					return NeoFrag()->comments->admin_comments($data['module'], $data['module_id'], FALSE);
				},
				'size'    => TRUE
			],
			[
				'content' => function($data){
					return $this->button()
								->tooltip($this->lang('see_comments'))
								->icon('fa-eye')
								->url($data['url'])
								->color('info')
								->compact()
								->outline();
				},
				'size'    => TRUE
			]
		])
		->data($comments)
		->no_data($this->lang('no_comments'))
		->sort_by(1)
		->display();
	}
}

/*
NeoFrag Alpha 0.1.5.3
./neofrag/modules/comments/controllers/admin.php
*/