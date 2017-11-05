<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'title' => [
		'label' => '{lang title}',
		'value' => $this->form->value('title'),
		'type'  => 'text',
		'rules' => 'required'
	]
];
