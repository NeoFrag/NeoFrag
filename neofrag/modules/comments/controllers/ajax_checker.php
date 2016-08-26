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

class m_comments_c_ajax_checker extends Controller_Module
{
	public function delete($comment_id)
	{

		$comment = $this->db	->select('user_id', 'module_id', 'module')
								->from('nf_comments')
								->where('comment_id', (int)$comment_id)
								->row();
		
		if ($comment && ($this->user('admin') || ($this->user() && $comment['user_id'] == $this->user('user_id'))))
		{
			return [$comment_id, $comment['module_id'], $comment['module']];
		}

		throw new Exception(NeoFrag::UNFOUND);
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/modules/comments/controllers/ajax_checker.php
*/