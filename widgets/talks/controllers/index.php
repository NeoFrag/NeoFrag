<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class w_talks_c_index extends Controller_Widget
{
	public function index($settings = [])
	{
		if (!$this->access('talks', 'read', $settings['talk_id']))
		{
			return;
		}

		$this	->js('talks')
				->css('talks')
				->js('jquery.mCustomScrollbar.min')
				->css('jquery.mCustomScrollbar.min');

		$panel = $this	->panel()
						->body('<div data-talk-id="'.$settings['talk_id'].'">'.$this->view('index', [
							'messages' => $this->model()->get_messages($settings['talk_id'])
						]).'</div>');

		if ($this->access('talks', 'write', $settings['talk_id']))
		{
			$panel->footer('<form>
								<div class="input-group">
									<input type="text" class="form-control" placeholder="'.$this->lang('your_message').'" />
									<span class="input-group-btn">
										<button class="btn btn-primary" type="submit">'.icon('fa-check').'</button>
									</span>
								</div>
							</form>');
		}

		return $panel;
	}
}
