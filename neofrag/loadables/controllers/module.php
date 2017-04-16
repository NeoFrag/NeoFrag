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
		$this->add_data('module_title', $title);
		return $this;
	}

	public function subtitle($subtitle)
	{
		$this->add_data('module_subtitle', $subtitle);
		return $this;
	}

	public function icon($icon)
	{
		$this->add_data('module_icon', $icon);
		return $this;
	}

	public function add_action($url, $title, $icon = '')
	{
		$this->load->caller->add_action($url, $title, $icon);
		return $this;
	}
}
