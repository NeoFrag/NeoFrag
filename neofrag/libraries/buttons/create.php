<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class Button_create extends Library
{
	public function __invoke($url, $title, $icon = 'fa-plus')
	{
		return $this->button()
					->title($title)
					->url($url)
					->icon($icon)
					->color('primary')
					->outline();
	}
}
