<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_pages_c_index extends Controller_Module
{
	public function _index($title, $subtitle, $content)
	{
		$this->title($title);
		
		return $this->panel()
					->heading($title.($subtitle ? ' <small>'.$subtitle.'</small>' : ''), 'fa-file-text-o')
					->body(bbcode($content));
	}
}
