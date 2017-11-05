<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class Button_update extends Library
{
	public function __invoke($url, $title = '')
	{
		return $this->button()
					->tooltip($title ?: $this->lang('edit'))
					->url($url)
					->icon('fa-pencil')
					->color('info')
					->compact()
					->outline();
	}
}
