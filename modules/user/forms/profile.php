<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$this	->rule($this->form_text('first_name')
					->title('Prénom')
					->size('col-6')
		)
		->rule($this->form_text('last_name')
					->title('Nom')
					->size('col-6')
		)
		->rule($this->form_date('date_of_birth')
					->title('Date de naissance')
					->check(function($post, $data){
						if (!is_empty($data['date_of_birth']) && $this->date($data['date_of_birth'])->diff() > 0)
						{
							return 'Date de naissance invalide';
						}
					})
					->size('col-6')
		)
		->rule($this->form_radio('sex')
					->title('Sexe')
					->data([
						'female' => 'Femme',
						'male'   => 'Homme'
					])
					->size('col-6')
		)
		->rule($this->form_select('country')
					->title('Pays')
					->data(get_countries())
					->size('col-6')
		)
		->rule($this->form_text('location')
					->title('Localisation')
					->size('col-6')
		)
		->rule($this->form_text('quote')
					->title('Citation')
		)
		->rule($this->form_bbcode('signature')
					->title('Signature')
					->rows(5)
		)
		->success(function($profile){
			$profile->commit();
			notify($this->lang('Profil modifié'));
			refresh();
		});
