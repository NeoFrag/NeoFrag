<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class Button_back extends Library
{
	public function __invoke($url = '', $title = '')
	{
		return $this->button()
					->title($title ?: $this->lang('back'))
					->url($this->session->get_back() ?: $url)
					->color('default');
	}
}
