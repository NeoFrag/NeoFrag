<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$this	->rule($this->form_url('website')
					->title('Site web')
		)
		->rule($this->form_text('linkedin')
					->title('Linkedin')
					->info('linkedin.com/in/<b>[xxxxx-xxx-xxx]</b>')
					->addon('fab fa-linkedin-in')
		)
		->rule($this->form_text('github')
					->title('GitHub')
					->info('Nom du compte GitHub')
					->addon('fab fa-github')
		)
		->rule($this->form_text('instagram')
					->title('Instagram')
					->info('Nom du compte Instagram')
					->addon('fab fa-instagram')
		)
		->rule($this->form_text('twitch')
					->title('Twitch')
					->info('Nom du compte Twitch')
					->addon('fab fa-twitch')
		)
		->success(function($profile){
			$profile->commit();
			notify($this->lang('Liens modifiés'));
			refresh();
		});
