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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

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
			return '<a href="{base_url}admin/comments/'.url_title($module_name).'/'.$module_id.'.html">'.$this->count_comments($module_name, $module_id).'</a>';
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
		$this	->css('neofrag.comments')
				->js('neofrag.comments')
				->load->library('form')
				->add_rules(array(
					'comment_id' => array(
					),
					'comment' => array(
						'rules' => 'required'
					)
				));
		
		if ($this->form->is_valid($post))
		{
			$parent_id = NULL;
			
			if (!empty($post['comment_id']) && $this->db->select('COUNT(*)')->from('nf_comments')->where('module', $module_name)->where('module_id', $module_id)->where('parent_id', NULL)->where('comment_id', $post['comment_id'])->row() == 1)
			{
				$parent_id = $post['comment_id'];
			}
			
			$comment_id = $this->db->insert('nf_comments', array(
				'parent_id' => $parent_id,
				'user_id'   => $this->user('user_id'),
				'module_id' => $module_id,
				'module'    => $module_name,
				'content'   => $post['comment']
			));
			
			redirect($this->config->request_url.'#comment-'.$comment_id);
		}

		$comments = $this->db	->select('c.*', 'u.username', 'up.avatar', 'up.sex')
								->from('nf_comments c')
								->join('nf_users u', 'u.user_id = c.user_id')
								->join('nf_users_profiles up', 'u.user_id = up.user_id')
								->where('module', $module_name)
								->where('module_id', $module_id)
								->order_by('IFNULL(c.parent_id, c.comment_id) DESC')
								->get();

		$output = '';
				
		foreach ($comments as $comment)
		{
			$output .= $this->load->view('index', $comment);
		}
		
		$count = count($comments);
		
		$panels = array();
		
		if ($errors = $this->form->get_errors())
		{
			$panels[] = new Panel(array(
				'title'   => '<a name="comments"></a>Veuillez remplir un message',
				'icon'    => 'fa-warning',
				'style'   => 'panel-danger'
			));
		}
		
		$panels[] = new Panel(array(
			'title'   => '<a name="comments"></a>'.$count.' '.($count > 1 ? 'Commentaires' : 'Commentaire'),
			'icon'    => 'fa-comments-o',
			'content' => $output.$this->load->view('new', array(
				'form_id' => $this->form->id
			))
		));
		
		return display($panels);
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/libraries/comments.php
*/