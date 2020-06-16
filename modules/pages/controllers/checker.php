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
		if ($this->url->segments[0] != 'pages' && ($content = $this->db->select('p.page_id', 'pl.title', 'pl.subtitle', 'pl.content')->from('nf_pages p')->join('nf_pages_lang pl', 'p.page_id = pl.page_id')->where('name', $name)->where('published', TRUE)->row()))
		{
			if ($this->access('pages', 'access_page', $content['page_id']))
			{
				return $content;
			}

			return $this->error->unauthorized();
		}
	}
}
