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
								'.($page == '*' ? '<button type="button" class="btn btn-link live-editor-fork" data-enabled="0">'.icon('fa-toggle-off').' '.$this->lang('Disposition commune').'</button>' : '<button type="button" class="btn btn-link live-editor-fork" data-enabled="1">'.icon('fa-toggle-on').' '.$this->lang('Disposition spécifique à la page').'</button>').'
							</div>
							<h3>'.(!empty(NeoFrag()->theme->info()->zones[$zone_id]) ? NeoFrag()->theme->info()->zones[$zone_id] : $this->lang('Zone #%d', $zone_id)).' <div class="btn-group"><button type="button" class="btn btn-xs btn-success live-editor-add-row" data-toggle="tooltip" data-container="body" title="'.$this->lang('Nouveau Row').'">'.icon('fa-plus').'</button></div></h3>'.
							$output;
			}

			$output = '<div'.(NeoFrag::live_editor() & NeoFrag::ZONES ? ' class="live-editor-zone"' : '').' data-disposition-id="'.$disposition_id.'">'.$output.'</div>';
		}

		return $output;
	}
}
