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

class m_comments_c_ajax extends Controller_Module
{
	public function delete($comment_id, $module_id, $module)
	{
		$this	->title($this('delete_confirmation'))
				->form
				->confirm_deletion($this('delete_confirmation'), $this('comment_confirmation'));

		if ($this->form->is_valid())
		{
			if ($this->db->select('comment_id')->from('nf_comments')->where('module', $module)->where('module_id', $module_id)->order_by('comment_id DESC')->limit(1)->row() == $comment_id)
			{
				$this->db	->where('comment_id', $comment_id)
							->delete('nf_comments');
			}
			else
			{
				$this->db	->where('comment_id', $comment_id)
							->update('nf_comments', [
								'content' => NULL
							]);
			}

			return 'OK';
		}

		echo $this->form->display();
	}
}

/*
NeoFrag Alpha 0.1.3
./neofrag/modules/comments/controllers/ajax.php
*/