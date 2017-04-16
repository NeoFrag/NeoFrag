<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class A_Authenticator extends Addon
{
	protected function __info()
	{
		return [
			'title'   => 'Authentificateur',
			'icon'    => 'fa-sign-in',
			'version' => '1.0',
			'depends' => [
				'neofrag' => 'Alpha 0.1.7'
			]
		];
	}
}
