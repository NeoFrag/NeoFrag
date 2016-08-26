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

class Row
{
	public $style;
	public $cols = [];
	
	public function __construct()
	{
		foreach (func_get_args() as $col)
		{
			if (is_a($col, 'Col'))
			{
				$this->cols[] = $col;
			}
			else if (is_string($col))
			{
				$this->style = $col;
			}
		}
	}
	
	public function delete_col($id)
	{
		unset($this->cols[$id]);
		return $this;
	}
	
	public function display($id = NULL)
	{
		$output = '';
		
		if ($live_editor = NeoFrag::live_editor() & NeoFrag::ROWS && $id !== NULL)
		{
			$output .= '<div class="live-editor-row-header">
							<div class="btn-group">
								<button type="button" class="btn btn-sm btn-info live-editor-style" data-toggle="tooltip" data-container="body" title="'.NeoFrag::loader()->lang('design').'">'.icon('fa-paint-brush').'</button>
								<button type="button" class="btn btn-sm btn-danger live-editor-delete" data-toggle="tooltip" data-container="body" title="'.NeoFrag::loader()->lang('remove').'">'.icon('fa-close').'</button>
							</div>
							<h3>'.NeoFrag::loader()->lang('row').' <div class="btn-group"><button type="button" class="btn btn-xs btn-success live-editor-add-col" data-toggle="tooltip" data-container="body" title="'.NeoFrag::loader()->lang('new_col').'">'.icon('fa-plus').'</button></div></h3>
						</div>';
		}
		
		$cols = [];
		foreach ($this->cols as $i => $col)
		{
			$cols[] = $col->display($id !== NULL ? $i : NULL);
		}
		
		$output .= '<div class="row'.(!empty($this->style) ? ' '.$this->style.($live_editor ? '" data-original-style="'.$this->style : '') : '').'"'.($id !== NULL ? ' data-row-id="'.$id.'"' : '').'>
						'.implode($cols).'
					</div>';
	
		return $live_editor ? '<div class="live-editor-row">'.$output.'</div>' : $output;
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/classes/row.php
*/