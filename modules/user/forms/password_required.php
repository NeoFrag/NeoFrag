<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$this	->rule($this->form_password('password')
					->title('Mot de passe')
					->required()
		)
		->rule($this->form_password('password_confirm')
					->title('Confirmation')
					->required()
					->check(function($data){
						if ($data['password'] && $data['password'] !== $data['password_confirm'])
						{
							return 'Les mots de passe de correspondent pas';
						}
					})
		);
