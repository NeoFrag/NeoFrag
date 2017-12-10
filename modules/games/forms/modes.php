<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$rules = [
	'title' => [
		'label' => 'Nom du mode',
		'value' => $this->form()->value('title'),
		'type'  => 'text',
		'rules' => 'required'
	]
];
