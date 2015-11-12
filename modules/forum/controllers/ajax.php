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
 
class m_forum_c_ajax extends Controller
{
	public function _topic_move($topic_id, $title, $forum_id)
	{
		return $this->load->view('move', array(
			'topic_id'   => $topic_id,
			'title'      => $title,
			'forum_id'   => $forum_id,
			'categories' => $this->model()->get_forums_tree()
		));
	}
}

/*
NeoFrag Alpha 0.1.3
./modules/forum/controllers/ajax.php
*/