<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Widgets\Talks\Controllers;

use NF\NeoFrag\Loadables\Controllers\Widget as Controller_Widget;

class Index extends Controller_Widget
{
	public function index($settings = [])
	{
		if (!$this->access('talks', 'read', $settings['talk_id']))
		{
			return;
		}

		$this->module('talks')->js('talks');

		$this	->css('talks')
				->js('jquery.mCustomScrollbar.min')
				->css('jquery.mCustomScrollbar.min');

		$panel = $this	->panel()
						->body('<div data-talk-id="'.$settings['talk_id'].'">'.$this->module('talks')->view('index', [
							'messages' => $this->module('talks')->model()->get_messages($settings['talk_id'])
						]).'</div>');

		if ($this->access('talks', 'write', $settings['talk_id']))
		{
			$panel->footer('<form>
								<div class="input-group">
									<input type="text" class="form-control" placeholder="'.$this->lang('Votre message...').'" />
									<span class="input-group-append">
										<button class="btn btn-primary" type="submit">'.icon('fas fa-check').'</button>
									</span>
								</div>
							</form>');
		}

		return $panel;
	}
}
