<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$this	->col(function($a){
			return user_agent($a->user_agent);
		})
		->col('Adresse IP', function($a){
						return geolocalisation($ip_address = $a->data->session->ip_address).'<span data-toggle="tooltip" data-original-title="'.$a->data->session->host_name.'">'.$ip_address.'</span>';
		})
		->col('Site référent', function($a){
			return ($referer = $a->data->session->referer) ? urltolink($referer) : $this->lang('Aucun');
		})
		->col('Date', 'last_activity')
		->col('Compte tiers', 'auth');
