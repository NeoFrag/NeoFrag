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

class Tab extends Library
{
	private $_tabs         = [];
	private $_default_tab  = '';

	public function add_tab($url, $name, $callback)
	{
		$this->_tabs[] = [
			'url'      => $url,
			'name'     => $name,
			'callback' => $callback,
		];

		return $this;
	}

	public function display($index)
	{
		$index = url_title($index);

		$tabs = [
			'panes'    => [],
			'sections' => []
		];

		$i = 0;
		foreach ($this->_tabs as $tab)
		{
			if (isset($tab['url']))
			{
				$tab['url'] = url_title($tab['url']);

				$tabs['sections'][] = [
					'active' => $tab['url'] == $index,
					'url'    => ($tab['url'] == $this->_default_tab) ? '' : $tab['url'],
					'name'   => $tab['name']
				];

				if ($tab['url'] == $index)
				{
					$tabs['panes'][] = [
						'id'       => $tab['url'],
						'callback' => $tab['callback']
					];
				}

				$i++;
			}
			else
			{
				$languages = $this->db	->select('code', 'name', 'flag')
										->from('nf_settings_languages')
										->order_by('order')
										->get();

				foreach ($languages as $lang)
				{
					$code = $index;

					if ($i == 0)
					{
						if ($index != array_last($this->url->segments))
						{
							$index = $code = $lang['code'];
						}
						else
						{
							$code = NULL;
						}
					}

					$tabs['sections'][] = [
						'active' => $lang['code'] == $code,
						'url'    => ($i == 0) ? '' : $lang['code'],
						'name'   => '<img src="'.image('flags/'.$lang['flag']).'" alt="" />'.$lang['name']
					];

					if ($lang['code'] == $index)
					{
						$tabs['panes'][] = [
							'id'       => $lang['code'],
							'callback' => $tab['callback']
						];
					}

					$i++;
				}
			}
		}

		foreach ($tabs['sections'] as $section)
		{
			if ($is_good = $section['active'])
			{
				break;
			}
		}

		if (!isset($is_good) || !$is_good || array_last($this->url->segments) == $this->_default_tab)
		{
			throw new Exception(NeoFrag::UNFOUND);
		}

		$segments = explode('/', $this->pagination->get_url());
		$base_url = implode('/', end($segments) == $index ? array_offset_right($segments) : $segments);

		$output = '	<div class="tabbable">
						<ul class="nav nav-tabs">';

		foreach ($tabs['sections'] as $section)
		{
			$output .= '	<li'.(($section['active']) ? ' class="active"' : '').'><a href="'.rtrim($base_url.'/'.$section['url'], '/').'.html">'.$section['name'].'</a></li>';
		}

		$output .= '	</ul>
						<div class="tab-content">';

		foreach ($tabs['panes'] as $pane)
		{
			$output .= '	<div class="tab-pane active" id="'.$pane['id'].'">
								'.$pane['callback']().'
							</div>';
		}

		$output .= '	</div>
					</div>';

		return $output;
	}
}

/*
NeoFrag Alpha 0.1.5.3
./neofrag/libraries/tab.php
*/