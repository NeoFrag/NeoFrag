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

class Row extends Childrenable
{
	protected $_style;

	public function style($style)
	{
		$this->_style = $style;
		return $this;
	}

	public function __toString()
	{
		$output = '';

		$live_editor = FAlSE;

		if ($this->_id !== NULL)
		{
			foreach ($this->_children as $i => $child)
			{
				$child->id($i);
			}

			if ($live_editor = NeoFrag::live_editor() & NeoFrag::ROWS)
			{
				$output .= '<div class="live-editor-row-header">
								<div class="btn-group">
									<button type="button" class="btn btn-sm btn-info live-editor-style" data-toggle="tooltip" data-container="body" title="'.NeoFrag::loader()->lang('design').'">'.icon('fa-paint-brush').'</button>
									<button type="button" class="btn btn-sm btn-danger live-editor-delete" data-toggle="tooltip" data-container="body" title="'.NeoFrag::loader()->lang('remove').'">'.icon('fa-close').'</button>
								</div>
								<h3>'.NeoFrag::loader()->lang('row').' <div class="btn-group"><button type="button" class="btn btn-xs btn-success live-editor-add-col" data-toggle="tooltip" data-container="body" title="'.NeoFrag::loader()->lang('new_col').'">'.icon('fa-plus').'</button></div></h3>
							</div>';
			}
		}

		$output .= '<div class="row'.(!empty($this->_style) ? ' '.$this->_style.($live_editor ? '" data-original-style="'.$this->_style : '') : '').'"'.($this->_id !== NULL ? ' data-row-id="'.$this->_id.'"' : '').'>
						'.implode($this->_children).'
					</div>';

		return $live_editor ? '<div class="live-editor-row">'.$output.'</div>' : $output;
	}
}

/*
NeoFrag Alpha 0.1.6
./neofrag/libraries/row.php
*/