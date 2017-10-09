<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Loadables\Controllers;

use NF\NeoFrag\Loadables\Controller;

abstract class Module extends Controller
{
	public function __construct($caller)
	{
		parent::__construct($this->module = $caller);
	}

	public function title($title)
	{
		$this->output->data->set('module', 'title', $title);
		return $this;
	}

	public function subtitle($subtitle)
	{
		$this->output->data->set('module', 'subtitle', $subtitle);
		return $this;
	}

	public function icon($icon)
	{
		$this->output->data->set('module', 'icon', $icon);
		return $this;
	}

	public function add_action($url, $title = '', $icon = '')
	{
		if (!is_a($url, 'NF\NeoFrag\Libraries\Html'))
		{
			$url = $this->button($title, $icon, 'primary', $url);
		}

		$this->output->data->append('module', 'actions', $url);
		return $this;
	}

	public function ajax()
	{
		$this->output->data->set('module', 'ajax', TRUE);
		return $this;
	}
}
