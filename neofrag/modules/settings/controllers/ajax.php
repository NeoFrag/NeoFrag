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
NeoFrag Alpha 0.1.5
./neofrag/modules/settings/controllers/ajax.php
*/