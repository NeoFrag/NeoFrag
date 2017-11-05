<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

abstract class Controller_Widget extends Controller
{
	abstract public function index($config = []);

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
