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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class Zone
{
	static public function display($disposition_id, $disposition, $page, $zone_id)
	{
		global $NeoFrag;

		$output = display($disposition, NeoFrag::live_editor() ? $zone_id : NULL);

		if (!NeoFrag::live_editor() && in_string('<div class="module', $output))
		{
			$output .= $NeoFrag->profiler->output();
		}

		if (NeoFrag::live_editor())
		{
			if (NeoFrag::live_editor() & NeoFrag::ZONES)
			{
				$output = '	<div class="pull-right">
								'.($page == '*' ? '<button type="button" class="btn btn-link live-editor-fork" data-enable="0"><i class="fa fa-toggle-off"></i> Disposition commune</button>' : '<button type="button" class="btn btn-link live-editor-fork" data-enable="1"><i class="fa fa-toggle-on"></i> Disposition spécifique à la page</button>').'
							</div>
							<h3>'.(!empty($NeoFrag->load->theme->zones[$zone_id]) ? $NeoFrag->load->theme->zones[$zone_id] : 'Zone #'.$zone_id).' <div class="btn-group"><button type="button" class="btn btn-xs btn-success live-editor-add-row" data-toggle="tooltip" title="Nouveau Row"><i class="fa fa-plus"></i></button></div></h3>'.
							$output;
			}

			$output = '<div'.(NeoFrag::live_editor() & NeoFrag::ZONES ? ' class="live-editor-zone"' : '').' data-disposition-id="'.$disposition_id.'">'.$output.'</div>';
		}

		return $output;
	}
}

/*
NeoFrag Alpha 0.1.1
./neofrag/classes/zone.php
*/