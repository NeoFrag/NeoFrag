<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_pages_c_checker extends Controller_Module
{
	public function _index($name)
	{
		if ($content = $this->db->select('pl.title', 'pl.subtitle', 'pl.content')->from('nf_pages p')->join('nf_pages_lang pl', 'p.page_id = pl.page_id')->where('name', $name)->where('published', TRUE)->row())
		{
			return $content;
		}
	}
}
