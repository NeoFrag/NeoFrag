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

class m_talks_c_admin extends Controller_Module
{
	public function index($talks)
	{
		$this	->table
				->add_columns([
					[
						'title'   => $this('talks'),
						'content' => function($data){
							return $data['name'];
						},
						'sort'    => function($data){
							return $data['name'];
						},
						'search'  => function($data){
							return $data['name'];
						}
					],
					[
						'content' => [
							function($data){
								if ($data['talk_id'] > 1)
								{
									return button_access($data['talk_id'], 'talk');
								}
							},
							function($data){
								if ($data['talk_id'] > 1)
								{
									return button_edit('admin/talks/'.$data['talk_id'].'/'.url_title($data['name']).'.html');
								}
							},
							function($data){
								if ($data['talk_id'] > 1)
								{
									return button_delete('admin/talks/delete/'.$data['talk_id'].'/'.url_title($data['name']).'.html');
								}
							}
						],
						'size'    => TRUE
					]
				])
				->data($talks)
				->no_data($this('no_talks'));
						
		return new Panel([
			'title'   => $this('talks_list'),
			'icon'    => 'fa-comment-o',
			'content' => $this->table->display(),
			'footer'  => button_add('admin/talks/add.html', $this('create_talk'))
		]);
	}
	
	public function add()
	{
		$this	->subtitle($this('add_talk'))
				->form
				->add_rules('talks')
				->add_submit($this('add'))
				->add_back('admin/talks.html');

		if ($this->form->is_valid($post))
		{
			$this->model()->add_talk($post['title']);
			
			notify($this('add_success_message'));

			redirect_back('admin/talks.html');
		}
		
		return new Panel([
			'title'   => $this('add_talk'),
			'icon'    => 'fa-comment-o',
			'content' => $this->form->display()
		]);
	}

	public function _edit($talk_id, $title)
	{
		$this	->subtitle($title)
				->form
				->add_rules('talks', [
					'title' => $title
				])
				->add_submit($this('edit'))
				->add_back('admin/talks.html');
		
		if ($this->form->is_valid($post))
		{	
			$this->model()->edit_talk($talk_id, $post['title']);
		
			notify($this('edit_success_message'));

			redirect_back('admin/talks.html');
		}
		
		return new Panel([
			'title'   => $this('edit_talk'),
			'icon'    => 'fa-comment-o',
			'content' => $this->form->display()
		]);
	}
	
	public function delete($talk_id, $title)
	{
		$this	->title($this('delete_talk_title'))
				->subtitle($title)
				->form
				->confirm_deletion($this('delete_confirmation'), $this('delete_talk', $title));

		if ($this->form->is_valid())
		{
			$this->model()->delete_talk($talk_id);

			return 'OK';
		}

		echo $this->form->display();
	}
}

/*
NeoFrag Alpha 0.1.4.1
./modules/talks/controllers/admin.php
*/