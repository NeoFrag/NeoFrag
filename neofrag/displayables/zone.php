<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Displayables;

use NF\NeoFrag\Displayable;

class Zone extends Displayable
{
	public function __invoke($disposition_id, $disposition, $page, $zone_id)
	{
		$output = display($disposition, NEOFRAG_LIVE_EDITOR ? $zone_id : NULL);

		if (NEOFRAG_LIVE_EDITOR)
		{
			if (NEOFRAG_LIVE_EDITOR & NEOFRAG_ZONES)
			{
				$output = '	<div class="pull-right">
								'.($page == '*' ? '<button type="button" class="btn btn-link live-editor-fork" data-enabled="0">'.icon('fa-toggle-off').' '.$this->lang('common_layout').'</button>' : '<button type="button" class="btn btn-link live-editor-fork" data-enabled="1">'.icon('fa-toggle-on').' '.$this->lang('custom_layout').'</button>').'
							</div>
							<h3>'.(!empty($this->output->theme()->info()->zones[$zone_id]) ? $this->output->theme()->lang($this->output->theme()->info()->zones[$zone_id], NULL) : $this->lang('zone', $zone_id)).' <div class="btn-group"><button type="button" class="btn btn-xs btn-success live-editor-add-row" data-toggle="tooltip" data-container="body" title="'.$this->lang('new_row').'">'.icon('fa-plus').'</button></div></h3>'.
							$output;
			}

			$output = '<div'.(NEOFRAG_LIVE_EDITOR & NEOFRAG_ZONES ? ' class="live-editor-zone"' : '').' data-disposition-id="'.$disposition_id.'">'.$output.'</div>';
		}

		return $output;
	}
}
