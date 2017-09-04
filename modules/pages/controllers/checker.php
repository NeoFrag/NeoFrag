<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Pages\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Checker extends Module_Checker
{
	public function _index($name)
	{
		if ($content = $this->db->select('pl.title', 'pl.subtitle', 'pl.content')->from('nf_pages p')->join('nf_pages_lang pl', 'p.page_id = pl.page_id')->where('name', $name)->where('published', TRUE)->row())
		{
			return $content;
		}
	}
}
