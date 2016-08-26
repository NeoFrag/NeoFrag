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
	private $_url_position = 'end';
	private $_default_tab  = 'default';

	public function add_tab($url, $name, $function, $args = [])
	{
		$this->_tabs[] = [
			'url'      => $url,
			'name'     => $name,
			'function' => $function,
			'args'     => $args ? array_offset_left(func_get_args(), 3) : $args
		];

		return $this;
	}
	
	public function add_tabs($tabs)
	 {
		$this->_tabs = array_merge($this->_tabs, $tabs);
		return $this;
	 }
	 
	public function add_translation_tabs($function)
	{
		$this->_tabs[] = [
			'name'     => 'translations',
			'function' => $function,
			'args'     => array_offset_left(func_get_args())
		];

		return $this;
	}

	public function url_position($position)
	{
		if (in_array($position, ['beginning', 'end']))
		{
			$this->_url_position = $position;
		}

		return $this;
	}

	public function default_tab($default)
	{
		$this->_default_tab = url_title($default);
		return $this;
	}

	public function display($index)
	{
		$index = url_title($index);
		$module_name = $this->config->segments_url[0];
		$base_url = implode('/', in_array($index, $segments = array_offset_left($this->config->segments_url)) ? ($this->_url_position == 'end' ? array_offset_right($segments) : array_offset_left($segments)) : $segments);

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
						'args'     => $tab['args'],
						'id'       => $tab['url'],
						'function' => $tab['function']
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
						if ($index != array_last($this->config->segments_url))
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
							'args'     => array_merge($tab['args'], [$index]),
							'id'       => $lang['code'],
							'function' => $tab['function']
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

		if (!isset($is_good) || !$is_good || array_last($this->config->segments_url) == $this->_default_tab)
		{
			throw new Exception(NeoFrag::UNFOUND);
		}

		$output = '	<div class="tabbable">
						<ul class="nav nav-tabs">';

		foreach ($tabs['sections'] as $section)
		{
			$output .= '	<li'.(($section['active']) ? ' class="active"' : '').'><a href="'.url($module_name.'/'.trim(($this->_url_position == 'end') ? $base_url.'/'.$section['url'] : $section['url'].'/'.$base_url, '/').'.html').'">'.$section['name'].'</a></li>';
		}

		$output .= '	</ul>
						<div class="tab-content">';

		$caller = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[1]['object'];

		foreach ($tabs['panes'] as $pane)
		{
			$output .= '	<div class="tab-pane active" id="'.$pane['id'].'">
								'.(string)$caller->method($pane['function'], $pane['args']).'
							</div>';
		}

		$output .= '	</div>
					</div>';

		return $output;
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/libraries/tab.php
*/