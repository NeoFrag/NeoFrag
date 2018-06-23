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
		/*->rule($this->form_file('avatar')
					->title('Avatar')
		)
		->rule($this->form_file('cover')
					->title('Photo de couverture')
		)*/
		->rule($this->form_date('date_of_birth')
					->title('Date de naissance')
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
		);
