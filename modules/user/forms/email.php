<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$this	->rule($this->form_email('email')
					->title('Email')
					->required()
					->check(function($data){
						if ($data['email'] && !$this->db()->from('nf_user')->where('email', $data['email'])->where('deleted', FALSE)->where_if($this->_values, 'id <>', $this->_values->id)->empty())
						{
							return 'Adresse email déjà utilisée';
						}
					})
		);
