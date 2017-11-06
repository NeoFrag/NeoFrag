<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Buttons;

use NF\NeoFrag\Library;

class Access extends Library
{
	public function __invoke($id, $access = '', $module = '', $title = '')
	{
		return $this->button()
					->tooltip($title ?: $this->lang('Permissions'))
					->url('admin/access/edit/'.($access ? ($module ?: $this->output->module()->name).'/'.$id.'-'.$access : $id))
					->icon('fa-unlock-alt')
					->color('success')
					->compact()
					->outline();
	}
}
