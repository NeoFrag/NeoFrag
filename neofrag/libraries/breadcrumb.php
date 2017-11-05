<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class Breadcrumb extends Library
{
	private $_links = [];

	public function get_links()
	{
		$links = $this->_links;

		if (empty($links) && $this->url->segments[0] == 'index')
		{
			array_unshift($links, [$this->lang('home'), '', 'fa-map-marker']);
		}
		else
		{
			array_unshift($links, [NeoFrag()->module->get_title(), NeoFrag()->module->name == 'pages' ? $this->url->request : NeoFrag()->module->name, NeoFrag()->module->icon ?: 'fa-map-marker']);
		}

		return $links;
	}

	public function __invoke($title = '', $link = '', $icon = '')
	{
		if ($title === '')
		{
			$title = !empty(NeoFrag()->module->load->data['module_title']) ? NeoFrag()->module->load->data['module_title'] : '';
		}

		if ($title !== '')
		{
			$this->_links[] = [$title, $link ?: $this->url->request, $icon ?: (!empty(NeoFrag()->module->load->data['module_icon']) ? NeoFrag()->module->load->data['module_icon'] : NeoFrag()->module->icon)];
		}

		return $this;
	}
}
