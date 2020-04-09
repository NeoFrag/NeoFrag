<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Tab extends Library
{
	private $_tabs         = [];
	private $_default_tab  = '';

	public function add_tab($url, $name, $callback)
	{
		$this->_tabs[] = [
			'url'      => $url,
			'name'     => $name,
			'callback' => $callback
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
			$this->error();
		}

		$segments = explode('/', $this->pagination->get_url());
		$base_url = implode('/', end($segments) == $index ? array_offset_right($segments) : $segments);

		$output = '	<div class="tabbable">
						<ul class="nav nav-tabs">';

		foreach ($tabs['sections'] as $section)
		{
			$output .= '	<li'.(($section['active']) ? ' class="active"' : '').'><a href="'.url(rtrim($base_url.'/'.$section['url'], '/')).'">'.$section['name'].'</a></li>';
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
