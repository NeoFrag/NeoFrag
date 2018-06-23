<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$this	->rule($this->form_text('email')
					->title('Email')
					->required()
					->check(function($data){
						if ($data['email'] && $this->db()->select('1')->from('nf_user')->where('email', $data['email'])->where('deleted', FALSE)->where_if($this->_values, 'id <>', $this->_values->id)->row())
						{
							return 'Adresse email déjà utilisée';
						}
					})
		);
