<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Loadables\Controllers;

use NF\NeoFrag\Loadables\Controller;

abstract class Widget extends Controller
{
	abstract public function index($config = []);

	public function __construct($caller)
	{
		parent::__construct($this->widget = $caller);
	}

	public function title($title)
	{
		$this->add_data('widget_title', $title);
		return $this;
	}

	public function subtitle($subtitle)
	{
		$this->add_data('widget_subtitle', $subtitle);
		return $this;
	}
}
