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

class m_forum_c_statistics extends Controller_Module
{
	public function statistics()
	{
		return [
			'topics' => [
				'title' => 'Nouveaux sujets',
				'data'  => function(){
					$this->db	->from('nf_forum_topics t')
								->join('nf_forum_messages m', 'm.message_id = t.message_id', 'INNER');
					
					return 'm.date';
				}
			],
			'replies' => [
				'title' => 'Nouvelles réponses',
				'data'  => function(){
					$this->db	->from('nf_forum_messages m')
								->join('nf_forum_topics t', 'm.topic_id = t.topic_id', 'INNER')
								->where('m.message_id <> t.message_id');
					
					return 'm.date';
				}
			]
		];
	}
}

/*
NeoFrag Alpha 0.1.5
./modules/forum/controllers/statistics.php
*/