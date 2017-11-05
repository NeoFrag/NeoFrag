<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class Button_access extends Library
{
	public function __invoke($id, $access = '', $module = '', $title = '')
	{
		return $this->button()
					->tooltip($title ?: $this->lang('permissions'))
					->url('admin/access/edit/'.($access ? ($module ?: NeoFrag()->module->name).'/'.$id.'-'.$access : $id))
					->icon('fa-unlock-alt')
					->color('success')
					->compact()
					->outline();
	}
}
