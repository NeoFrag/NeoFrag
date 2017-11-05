<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class Button_delete extends Library
{
	public function __invoke($url, $title = NULL)
	{
		return $this->css('neofrag.delete')
					->js('neofrag.delete')
					->button()
					->tooltip($title ?: $this->lang('remove'))
					->url($url)
					->icon('fa-remove')
					->color('danger')
					->style('delete')
					->compact()
					->outline();
	}
}
