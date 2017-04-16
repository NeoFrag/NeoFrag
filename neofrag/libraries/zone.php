<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class Zone extends Library
{
	public function __invoke($disposition_id, $disposition, $page, $zone_id)
	{
		$output = display($disposition, NeoFrag::live_editor() ? $zone_id : NULL);

		if (NeoFrag::live_editor())
		{
			if (NeoFrag::live_editor() & NeoFrag::ZONES)
			{
				$output = '	<div class="pull-right">
								'.($page == '*' ? '<button type="button" class="btn btn-link live-editor-fork" data-enabled="0">'.icon('fa-toggle-off').' '.$this->lang('common_layout').'</button>' : '<button type="button" class="btn btn-link live-editor-fork" data-enabled="1">'.icon('fa-toggle-on').' '.$this->lang('custom_layout').'</button>').'
							</div>
							<h3>'.(!empty(NeoFrag()->theme->info()->zones[$zone_id]) ? NeoFrag()->theme->lang(NeoFrag()->theme->info()->zones[$zone_id], NULL) : $this->lang('zone', $zone_id)).' <div class="btn-group"><button type="button" class="btn btn-xs btn-success live-editor-add-row" data-toggle="tooltip" data-container="body" title="'.$this->lang('new_row').'">'.icon('fa-plus').'</button></div></h3>'.
							$output;
			}

			$output = '<div'.(NeoFrag::live_editor() & NeoFrag::ZONES ? ' class="live-editor-zone"' : '').' data-disposition-id="'.$disposition_id.'">'.$output.'</div>';
		}

		return $output;
	}
}
