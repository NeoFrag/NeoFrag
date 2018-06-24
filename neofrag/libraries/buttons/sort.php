<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Buttons;

use NF\NeoFrag\Library;

class Sort extends Library
{
	public function __invoke($id, $url, $parent = 'table', $items = 'tr')
	{
		return $this->js('sortable')
					->button()
					->tooltip($this->lang('Ordonner'))
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
