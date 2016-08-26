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

class Col
{
	private $_size = 'col-md-12';
	
	public  $widgets = [];
	
	public function __construct()
	{
		$this->_init(func_get_args());
	}
	
	private function _init($args)
	{
		foreach ($args as $widget)
		{
			if (is_array($widget))
			{
				$this->_init($widget);
			}
			else if (is_string($widget))
			{
				$this->_size = $widget;
			}
			else if ($widget !== NULL)
			{
				$this->widgets[] = $widget;
			}
		}
	}
	
	public function set_size($size)
	{
		$this->_size = 'col-md-'.min(12, max($size, 1));
		return $this;
	}

	public function delete_widget($id)
	{
		unset($this->widgets[$id]);
		return $this;
	}
	
	public function display($id = NULL)
	{
		$output = '';
		
		foreach ($this->widgets as $i => $widget)
		{
			if (!empty($widget->size))
			{
				$this->_size = $widget->size;
			}
			
			$output .= $widget->display($id !== NULL ? $i : NULL);
		}
		
		if (NeoFrag::live_editor() & NeoFrag::COLS && $id !== NULL)
		{
			$output = '<div class="live-editor-col">
							<div class="btn-group">
								<button type="button" class="btn btn-sm btn-default live-editor-size" data-size="-1" data-toggle="tooltip" data-container="body" title="'.NeoFrag::loader()->lang('reduce').'">'.icon('fa-compress fa-rotate-45').'</button>
								<button type="button" class="btn btn-sm btn-default live-editor-size" data-size="1" data-toggle="tooltip" data-container="body" title="'.NeoFrag::loader()->lang('increase').'">'.icon('fa-expand fa-rotate-45').'</button>
								<button type="button" class="btn btn-sm btn-danger live-editor-delete" data-toggle="tooltip" data-container="body" title="'.NeoFrag::loader()->lang('remove').'">'.icon('fa-close').'</button>
							</div>
							<h3>'.NeoFrag::loader()->lang('col').' <div class="btn-group"><button type="button" class="btn btn-xs btn-success live-editor-add-widget" data-toggle="tooltip" data-container="body" title="'.NeoFrag::loader()->lang('new_widget').'">'.icon('fa-plus').'</button></div></h3>
							'.$output.'
						</div>';
		}
		
		return '<div class="'.$this->_size.'"'.($id !== NULL ? ' data-col-id="'.$id.'"' : '').'>'.$output.'</div>';
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/classes/col.php
*/