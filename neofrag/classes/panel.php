<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class Panel
{
	public function __construct($data)
	{
		foreach ($data as $var => $value)
		{
			$this->$var = $value;
		}
	}

	public function display($id = NULL)
	{
		$style = !empty($this->style) ? $this->style : 'panel-default';
		
		return '<div class="panel '.$style.'"'.($id !== NULL ? ' data-original-style="'.$style.'"' : '').'>
				'.(!empty($this->title) ? '<div class="panel-heading"><h3 class="panel-title">'.(!empty($this->url) ? '<a href="'.url($this->url).'">' : '').(!empty($this->icon) ? icon($this->icon).' ' : '').$this->title.(!empty($this->url) ? '</a>' : '').'</h3></div>' : '').'
				'.(!empty($this->form) ? '<form action="" method="post">' : '').'
				'.($body = !empty($this->content) && (!isset($this->body) || $this->body) ? '<div class="panel-body">' : '').'
				'.(!empty($this->content) ? $this->content : '').'
				'.($body ? '</div>' : '').'
				'.(!empty($this->footer) ? '<div class="panel-footer text-'.(!empty($this->footer_align) && in_array($this->footer_align, ['left', 'right']) ? $this->footer_align : 'center').'">'.$this->footer.'</div>' : '').'
				'.(!empty($this->form) ? '</form>' : '').'
			</div>';
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/classes/panel.php
*/