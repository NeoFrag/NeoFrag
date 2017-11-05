<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class Button_sort extends Library
{
	public function __invoke($id, $url, $parent = 'table', $items = 'tr')
	{
		return $this->js('neofrag.sortable')
					->button()
					->tooltip($this->lang('sort'))
					->icon('fa-arrows-v')
					->color('link')
					->style('btn-sortable')
					->data([
						'id'     => $id,
						'update' => url($url),
						'parent' => $parent,
						'items'  => $items
					])
					->compact()
					->outline();
	}
}
