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

class m_settings_c_ajax extends Controller_Module
{
	public function pagination()
	{
		if (in_array($items_per_page = post('items_per_page'), [0, 10, 25, 50, 100]))
		{
			if (($table_id = post('table_id')))
			{
				$this->session->set('table', $table_id, 'items_per_page', (int)$items_per_page);
			}
			else
			{
				$this->session->set('pagination', (int)$items_per_page);
			}

			if ($items_per_page == 0)
			{
				echo 'all';
			}
			else
			{
				echo 'p1-'.$items_per_page;
			}

			return;
		}

		echo 'OK';
	}

	public function language()
	{
		if ($this->user())
		{
			$this->db	->where('user_id', $this->_user_data['user_id'])
						->update('nf_users', [
							'language' => post('language')
						]);
		}
		else
		{
			$this->session->set('language', post('language'));
		}

		echo 'OK';
	}

	public function humans()
	{
		$this->extension('txt');

		if ($this->config->request_url != 'humans.txt' || !$this->config->nf_humans_txt)
		{
			throw new Exception(NeoFrag::UNFOUND);
		}

		echo $this->config->nf_humans_txt;
	}

	public function robots()
	{
		$this->extension('txt');

		if ($this->config->request_url != 'robots.txt' || !$this->config->nf_robots_txt)
		{
			throw new Exception(NeoFrag::UNFOUND);
		}

		echo $this->config->nf_robots_txt;
	}

	public function debugbar()
	{
		if ($tab = post('tab'))
		{
			$this->session->set('debugbar', 'tab', $tab);
		}
		else if ($tab === '')
		{
			$this->session->destroy('debugbar', 'tab');
		}
		else if ($height = (int)post('height'))
		{
			$this->session->set('debugbar', 'height', $height);
		}
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/modules/settings/controllers/ajax.php
*/