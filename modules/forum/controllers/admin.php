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
 
class m_forum_c_admin extends Controller_Module
{
	public function index()
	{
		$this	->subtitle($this('forums_list'))
				->css('forum')
				->js('forum')
				->add_action('admin/forum/categories/add.html', $this('add_category'), 'fa-plus')
				->add_action('admin/forum/add.html',            $this('add_forum'),    'fa-plus');
		
		$panels = [];
		
		foreach ($this->model()->get_categories() as $category)
		{
			$panels[] = new Panel([
				'content' => $this->load->view('index', $category),
				'body'    => FALSE
			]);
		}
		
		if (empty($panels))
		{
			$panels[] = new Panel([
				'title'   => $this('forum'),
				'icon'    => 'fa-comments',
				'style'   => 'panel-info',
				'content' => '<div class="text-center">'.$this('no_forum').'</div>'
			]);
		}

		return '<div id="forums-list">'.display($panels).'</div>';
	}
	
	public function add()
	{
		$this	->subtitle($this('add_forum'))
				->form
				->add_rules('forum', [
					'categories' => $this->model()->get_categories_list(),
				])
				->add_submit($this('add'))
				->add_back('admin/forum.html');

		if ($this->form->is_valid($post))
		{
			$this->model()->add_forum(	$post['title'],
										$post['category'],
										$post['description'],
										$post['url']);

			notify($this('add_forum_success'));

			redirect_back('admin/forum.html');
		}

		return new Panel([
			'title'   => $this('add_forum'),
			'icon'    => 'fa-comments',
			'content' => $this->form->display()
		]);
	}

	public function _edit($forum_id, $title, $description, $parent_id, $is_subforum, $url)
	{
		$this	->title($this('edit_forum'))
				->subtitle($title)
				->form
				->add_rules('forum', [
					'title'        => $title,
					'description'  => $description,
					'category_id'  => ($is_subforum ? 'f' : '').$parent_id,
					'categories'   => $this->model()->get_categories_list($forum_id),
					'url'          => $url
				])
				->add_submit($this('edit'))
				->add_back('admin/forum.html');

		if ($this->form->is_valid($post))
		{
			$this->db	->where('forum_id', $forum_id)
						->update('nf_forum', [
							'title'       => $post['title'],
							'parent_id'   => $this->model()->get_parent_id($post['category'], $is_subforum),
							'is_subforum' => $is_subforum,
							'description' => $post['description']
						]);

			if ($post['url'])
			{
				if ($url)
				{
					$this->db	->where('forum_id', $forum_id)
								->update('nf_forum_url', [
									'url' => $post['url']
								]);
				}
				else
				{
					$this->db->insert('nf_forum_url', [
						'forum_id' => $forum_id,
						'url'      => $post['url']
					]);
				}
			}
			else if ($url)
			{
				$this->db	->where('forum_id', $forum_id)
							->delete('nf_forum_url');
			}

			notify($this('edit_forum_success'));

			redirect_back('admin/forum.html');
		}

		return new Panel([
			'title'   => $this('edit_forum'),
			'icon'    => 'fa-comments',
			'content' => $this->form->display()
		]);
	}

	public function delete($forum_id, $title)
	{
		$this	->title($this('remove_forum'))
				->subtitle($title)
				->form
				->confirm_deletion($this('delete_confirmation'), $this('forum_confirmation', $title));

		if ($this->form->is_valid())
		{
			$this->model()->delete_forum($forum_id);

			return 'OK';
		}

		echo $this->form->display();
	}
	
	public function _categories_add()
	{
		$this	->subtitle($this('add_category'))
				->form
				->add_rules('categories')
				->add_back('admin/forum.html')
				->add_submit($this('add'));

		if ($this->form->is_valid($post))
		{
			$this->model()->add_category($post['title']);

			notify($this('add_category_success'));

			redirect_back('admin/forum.html');
		}
		
		return new Panel([
			'title'   => $this('add_category'),
			'icon'    => 'fa-comments',
			'content' => $this->form->display()
		]);
	}
	
	public function _categories_edit($category_id, $title)
	{
		$this	->title($this('edit_category'))
				->subtitle($title)
				->form
				->add_rules('categories', [
					'title' => $title
				])
				->add_submit($this('edit'))
				->add_back('admin/forum.html');
		
		if ($this->form->is_valid($post))
		{
			$this->model()->edit_category($category_id, $post['title']);
		
			notify($this('edit_category_success'));

			redirect_back('admin/forum.html');
		}
		
		return new Panel([
			'title'   => $this('edit_category'),
			'icon'    => 'fa-comments',
			'content' => $this->form->display()
		]);
	}
	
	public function _categories_delete($category_id, $title)
	{
		$this	->title($this('remove_category'))
				->subtitle($title)
				->form
				->confirm_deletion($this('delete_confirmation'), $this('category_confirmation', $title));
				
		if ($this->form->is_valid())
		{
			$this->model()->delete_category($category_id);

			return 'OK';
		}

		echo $this->form->display();
	}
}

/*
NeoFrag Alpha 0.1.4.1
./modules/forum/controllers/admin.php
*/