<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Comments extends Library
{
	public function count_comments($module_name, $module_id)
	{
		return $this->db->select('COUNT(*)')
						->from('nf_comments')
						->where('module', $module_name)
						->where('module_id', $module_id)
						->row();
	}

	public function admin_comments($module_name, $module_id, $link = TRUE)
	{
		if ($link)
		{
			return '<a href="'.url('admin/comments/'.url_title($module_name).'/'.$module_id).'">'.$this->count_comments($module_name, $module_id).'</a>';
		}
		else
		{
			return $this->count_comments($module_name, $module_id);
		}
	}

	public function delete($module_name, $module_id)
	{
		$this->db	->where('module', $module_name)
					->where('module_id', $module_id)
					->delete('nf_comments');

		return $this;
	}

	public function display($module_name, $module_id)
	{
		$this->form()->save();

		$form = $this	->css('comments')
						->js('comments')
						->form()
						->add_rules([
							'comment_id' => [
							],
							'comment' => [
								'rules' => 'required'
							]
						]);

		if ($form->is_valid($post))
		{
			$parent_id = NULL;

			if (!empty($post['comment_id']) && $this->db->select('COUNT(*)')->from('nf_comments')->where('module', $module_name)->where('module_id', $module_id)->where('parent_id', NULL)->where('comment_id', $post['comment_id'])->row() == 1)
			{
				$parent_id = $post['comment_id'];
			}

			$comment_id = $this->db->insert('nf_comments', [
				'parent_id' => $parent_id,
				'user_id'   => $this->user->id,
				'module_id' => $module_id,
				'module'    => $module_name,
				'content'   => $post['comment']
			]);

			redirect($this->url->request.'#comment-'.$comment_id);
		}

		$comments = $this->db	->select('c.comment_id', 'c.parent_id', 'u.id as user_id', 'c.module_id', 'c.module', 'c.content', 'c.date', 'u.username', 'up.avatar', 'up.sex')
								->from('nf_comments c')
								->join('nf_user u', 'u.id = c.user_id AND u.deleted = "0"')
								->join('nf_user_profile up', 'u.id = up.user_id')
								->where('module', $module_name)
								->where('module_id', $module_id)
								->order_by('IFNULL(c.parent_id, c.comment_id) DESC')
								->get();

		$output = '';

		foreach ($comments as $comment)
		{
			$output .= $this->view('comments/index', $comment);
		}

		$count = count($comments);

		$panels = $this->array;

		if ($errors = $form->get_errors())
		{
			$panels->append($this	->panel()
								->heading('<a name="comments"></a>'.NeoFrag()->lang('Veuillez remplir un message'), 'fa-warning')
									->color('danger'));
		}

		$panels->append($this	->panel()
							->heading('<a name="comments"></a>'.NeoFrag()->lang('%d Commentaire|%d Commentaires', $count, $count), 'fa-comments-o')
								->body($output.$this->view('comments/new', [
									'form_id' => $form->token()
								])));

		return $panels;
	}
}
