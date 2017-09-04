<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Forum\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin extends Controller_Module
{
	public function index()
	{
		$this	->subtitle($this->lang('forums_list'))
				->css('forum')
				->js('forum')
				->add_action('admin/forum/categories/add', $this->lang('add_category'), 'fa-plus')
				->add_action('admin/forum/add',            $this->lang('add_forum'),    'fa-plus');

		$panels = [];

		foreach ($this->model()->get_categories() as $category)
		{
			$panels[] = $this	->panel()
								->body($this->view('index', $category), FALSE);
		}

		if (empty($panels))
		{
			$panels[] = $this	->panel()
								->heading($this->lang('forum'), 'fa-comments')
								->body('<div class="text-center">'.$this->lang('no_forum').'</div>')
								->color('info');
		}

		return '<div id="forums-list">'.display($panels).'</div>';
	}

	public function add()
	{
		$this	->subtitle($this->lang('add_forum'))
				->form
				->add_rules('forum', [
					'categories' => $this->model()->get_categories_list()
				])
				->add_submit($this->lang('add'))
				->add_back('admin/forum');

		if ($this->form->is_valid($post))
		{
			$this->model()->add_forum(	$post['title'],
										$post['category'],
										$post['description'],
										$post['url']);

			notify($this->lang('add_forum_success'));

			redirect_back('admin/forum');
		}

		return $this->panel()
					->heading($this->lang('add_forum'), 'fa-comments')
					->body($this->form->display());
	}

	public function _edit($forum_id, $title, $description, $parent_id, $is_subforum, $url)
	{
		$this	->title($this->lang('edit_forum'))
				->subtitle($title)
				->form
				->add_rules('forum', [
					'title'        => $title,
					'description'  => $description,
					'category_id'  => ($is_subforum ? 'f' : '').$parent_id,
					'categories'   => $this->model()->get_categories_list($forum_id),
					'url'          => $url
				])
				->add_submit($this->lang('edit'))
				->add_back('admin/forum');

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

			notify($this->lang('edit_forum_success'));

			redirect_back('admin/forum');
		}

		return $this->panel()
					->heading($this->lang('edit_forum'), 'fa-comments')
					->body($this->form->display());
	}

	public function delete($forum_id, $title)
	{
		$this	->title($this->lang('remove_forum'))
				->subtitle($title)
				->form
				->confirm_deletion($this->lang('delete_confirmation'), $this->lang('forum_confirmation', $title));

		if ($this->form->is_valid())
		{
			$this->model()->delete_forum($forum_id);

			return 'OK';
		}

		echo $this->form->display();
	}

	public function _categories_add()
	{
		$this	->subtitle($this->lang('add_category'))
				->form
				->add_rules('categories')
				->add_back('admin/forum')
				->add_submit($this->lang('add'));

		if ($this->form->is_valid($post))
		{
			$this->model()->add_category($post['title']);

			notify($this->lang('add_category_success'));

			redirect_back('admin/forum');
		}

		return $this->panel()
					->heading($this->lang('add_category'), 'fa-comments')
					->body($this->form->display());
	}

	public function _categories_edit($category_id, $title)
	{
		$this	->title($this->lang('edit_category'))
				->subtitle($title)
				->form
				->add_rules('categories', [
					'title' => $title
				])
				->add_submit($this->lang('edit'))
				->add_back('admin/forum');

		if ($this->form->is_valid($post))
		{
			$this->model()->edit_category($category_id, $post['title']);

			notify($this->lang('edit_category_success'));

			redirect_back('admin/forum');
		}

		return $this->panel()
					->heading($this->lang('edit_category'), 'fa-comments')
					->body($this->form->display());
	}

	public function _categories_delete($category_id, $title)
	{
		$this	->title($this->lang('remove_category'))
				->subtitle($title)
				->form
				->confirm_deletion($this->lang('delete_confirmation'), $this->lang('category_confirmation', $title));

		if ($this->form->is_valid())
		{
			$this->model()->delete_category($category_id);

			return 'OK';
		}

		echo $this->form->display();
	}
}
