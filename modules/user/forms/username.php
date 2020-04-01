<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$this	->rule($this->form_text('username')
					->title('Identifiant')
					->required()
					->check(function($data){
						if ($data['username'] && !$this->db()->from('nf_user')->where('username', $data['username'])->where('deleted', FALSE)->where_if($this->_values, 'id <>', $this->_values->id)->empty())
						{
							return 'Identifiant déjà pris';
						}
					})
		);
