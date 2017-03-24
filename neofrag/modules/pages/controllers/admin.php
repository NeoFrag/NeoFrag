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

class m_pages_c_admin extends Controller_Module
{
	public function index($pages)
	{
		$this	->table
				->add_columns([
					[
						'content' => function($data){
							return $data['published'] ? '<i class="fa fa-circle" data-toggle="tooltip" title="'.$this->lang('published').'" style="color: #7bbb17;"></i>' : '<i class="fa fa-circle-o" data-toggle="tooltip" title="'.$this->lang('awaiting_publication').'" style="color: #535353;"></i>';
						},
						'sort'    => function($data){
							return $data['published'];
						},
						'size'    => TRUE
					],
					[
						'title'   => $this->lang('page_title'),
						'content' => function($data){
							return $data['published'] ? '<a href="'.url($data['name']).'">'.$data['title'].'</a> <small class="text-muted">'.$data['subtitle'].'</small>' : $data['title'];
						},
						'sort'    => function($data){
							return $data['title'];
						},
						'search'  => function($data){
							return $data['title'];
						}
					],
					[
						'content' => [
							function($data){
								return $data['published'] ? $this->button()->tooltip($this->lang('view_page'))->icon('fa-eye')->url($data['name'])->compact()->outline() : '';
							},
							function($data){
								return $this->button_update('admin/pages/'.$data['page_id'].'/'.url_title($data['title']));
							},
							function($data){
								return $this->button_delete('admin/pages/delete/'.$data['page_id'].'/'.url_title($data['title']));
							}
						],
						'size'    => TRUE
					]
				])
				->data($pages)
				->no_data($this->lang('no_pages'));
						
		return $this->panel()
					->heading($this->lang('list_pages'), 'fa-align-left')
					->body($this->table->display())
					->footer($this->button_create('admin/pages/add', $this->lang('create_page')));
	}
	
	public function add()
	{
		$this	->subtitle($this->lang('add_pages'))
				->form
				->add_rules('pages')
				->add_submit($this->lang('add'))
				->add_back('admin/pages');

		if ($this->form->is_valid($post))
		{
			$this->model()->add_page(	$post['name'],
										$post['title'],
										in_array('on', $post['published']),
										$post['subtitle'],
										$post['content']);

			notify($this->lang('add_success_message'));

			redirect_back('admin/pages');
		}
		
		return $this->panel()
					->heading($this->lang('add_pages'), 'fa-align-left')
					->body($this->form->display());
	}

	public function _edit($page_id, $name, $published, $title, $subtitle, $content, $tab)
	{
		$this	->subtitle($title)
				->form
				->add_rules('pages', [
					'title'          => $title,
					'subtitle'       => $subtitle,
					'name'           => $name,
					'content'        => $content,
					'published'      => $published
				])
				->add_submit($this->lang('edit'))
				->add_back('admin/pages');
		
		if ($this->form->is_valid($post))
		{	
			$this->model()->edit_page(	$page_id,
										$post['name'],
										$post['title'],
										in_array('on', $post['published']),
										$post['subtitle'],
										$post['content'],
										$this->config->lang);
		
			notify($this->lang('edit_success_message'));

			redirect_back('admin/pages');
		}
		
		return $this->panel()
					->heading($this->lang('edit_page'), 'fa-align-left')
					->body($this->form->display());
	}
	
	public function delete($page_id, $title)
	{
		$this	->title($this->lang('delete_page'))
				->subtitle($title)
				->form
				->confirm_deletion($this->lang('delete_confirmation'), $this->lang('delete_page_message', $title));

		if ($this->form->is_valid())
		{
			$this->model()->delete_page($page_id);

			return 'OK';
		}

		echo $this->form->display();
	}
}

/*
NeoFrag Alpha 0.1.6
./neofrag/modules/pages/controllers/admin.php
*/