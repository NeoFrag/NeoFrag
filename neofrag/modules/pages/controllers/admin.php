<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

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
