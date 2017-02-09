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

class Button_dropdown extends Button
{
	protected $_dropdown = [];

	public function __invoke()
	{
		parent::__invoke();

		$this->_container = function($content){
			$dropdown = '';

			if ($this->_dropdown)
			{
				$dropdown = $this	->html('ul')
									->attr('class', 'dropdown-menu')
									->content(array_map(function($a){
										return $this->html('li')->content($a);
									}, $this->_dropdown));
			}

			return $this->html()
						->attr('class', 'btn-group')
						->content($content.$dropdown);
		};

		$this->_template[] = function(&$content, &$attrs, &$tag){
			$content .= ' <span class="caret"></span>';
			$attrs['type'] = 'button';
			$tag = 'button';
		};

		return $this->attr('class', 'dropdown-toggle')
					->data('toggle', 'dropdown');
	}

	public function dropdown($dropdown)
	{
		$this->_dropdown = $dropdown;
		return $this;
	}
}

/*
NeoFrag Alpha 0.1.6
./neofrag/libraries/buttons/dropdown.php
*/