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
					->addon('fa-linkedin')
		)
		->rule($this->form_text('github')
					->title('GitHub')
					->info('Nom du compte GitHub')
					->addon('fa-github')
		)
		->rule($this->form_text('instagram')
					->title('Instagram')
					->info('Nom du compte Instagram')
					->addon('fa-instagram')
		)
		->rule($this->form_text('twitch')
					->title('Twitch')
					->info('Nom du compte Twitch')
					->addon('fa-twitch')
		);
