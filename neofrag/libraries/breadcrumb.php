<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Breadcrumb extends Library
{
	public function __invoke($title = '', $link = '', $icon = '')
	{
		if ($title === '')
		{
			//$title = !empty($this->output->module()->load->data['module_title']) ? $this->output->module()->load->data['module_title'] : '';
		}

		if ($title !== '')
		{
			$this->output->append('breadcrumb', [
				$title,
				$link ?: $this->url->request,
				$icon ?: (!empty($this->output->module()->load->data['module_icon']) ? $this->output->module()->load->data['module_icon'] : $this->output->module()->info()->icon)
			]);
		}

		return $this;
	}
}
