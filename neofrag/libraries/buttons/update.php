<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Buttons;

use NF\NeoFrag\Library;

class Update extends Library
{
	public function __invoke($url = '', $title = '')
	{
		return $this->button()
					->tooltip($title ?: $this->lang('Éditer'))
					->url($url)
					->icon('fas fa-pencil-alt')
					->color('info')
					->compact()
					->outline();
	}
}
