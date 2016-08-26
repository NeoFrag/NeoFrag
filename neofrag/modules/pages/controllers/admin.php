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
						'content' => function($data, $loader){
							return $data['published'] ? '<i class="fa fa-circle" data-toggle="tooltip" title="'.$loader->lang('published').'" style="color: #7bbb17;"></i>' : '<i class="fa fa-circle-o" data-toggle="tooltip" title="'.$loader->lang('awaiting_publication').'" style="color: #535353;"></i>';
						},
						'sort'    => function($data){
							return $data['published'];
						},
						'size'    => TRUE
					],
					[
						'title'   => $this('page_title'),
						'content' => function($data){
							return $data['published'] ? '<a href="'.url($data['name'].'.html').'">'.$data['title'].'</a> <small class="text-muted">'.$data['subtitle'].'</small>' : $data['title'];
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
							function($data, $loader){
								return $data['published'] ? button($data['name'].'.html', 'fa-eye', $loader->lang('view_page')) : '';
							},
							function($data){
								return button_edit('admin/pages/'.$data['page_id'].'/'.url_title($data['title']).'.html');
							},
							function($data){
								return button_delete('admin/pages/delete/'.$data['page_id'].'/'.url_title($data['title']).'.html');
							}
						],
						'size'    => TRUE
					]
				])
				->data($pages)
				->no_data($this('no_pages'));
						
		return new Panel([
			'title'   => $this('list_pages'),
			'icon'    => 'fa-align-left',
			'content' => $this->table->display(),
			'footer'  => button_add('admin/pages/add.html', $this('create_page'))
		]);
	}
	
	public function add()
	{
		$this	->subtitle($this('add_pages'))
				->form
				->add_rules('pages')
				->add_submit($this('add'))
				->add_back('admin/pages.html');

		if ($this->form->is_valid($post))
		{
			$this->model()->add_page(	$post['name'],
										$post['title'],
										in_array('on', $post['published']),
										$post['subtitle'],
										$post['content']);

			notify($this('add_success_message'));

			redirect_back('admin/pages.html');
		}
		
		return new Panel([
			'title'   => $this('add_pages'),
			'icon'    => 'fa-align-left',
			'content' => $this->form->display()
		]);
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
				->add_submit($this('edit'))
				->add_back('admin/pages.html');
		
		if ($this->form->is_valid($post))
		{	
			$this->model()->edit_page(	$page_id,
										$post['name'],
										$post['title'],
										in_array('on', $post['published']),
										$post['subtitle'],
										$post['content'],
										$this->config->lang);
		
			notify($this('edit_success_message'));

			redirect_back('admin/pages.html');
		}
		
		return new Panel([
			'title'   => $this('edit_page'),
			'icon'    => 'fa-align-left',
			'content' => $this->form->display()
		]);
	}
	
	public function delete($page_id, $title)
	{
		$this	->title($this('delete_page'))
				->subtitle($title)
				->form
				->confirm_deletion($this('delete_confirmation'), $this('delete_page_message', $title));

		if ($this->form->is_valid())
		{
			$this->model()->delete_page($page_id);

			return 'OK';
		}

		echo $this->form->display();
	}
}

/*
NeoFrag Alpha 0.1.4.1
./neofrag/modules/pages/controllers/admin.php
*/