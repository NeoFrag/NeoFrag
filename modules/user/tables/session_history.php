<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$this	->col(function($session_history){
			return user_agent($session_history->user_agent);
		})
		->col('Adresse IP', function($session_history){
			return geolocalisation($ip_address = $session_history->ip_address).'<span data-toggle="tooltip" data-original-title="'.$session_history->host_name.'">'.$ip_address.'</span>';
		})
		->col('Site référent', function($session_history){
			return ($referer = $session_history->referer) ? urltolink($referer) : $this->lang('Aucun');
		})
		->col('Date', 'date')
		->col('Compte tiers', 'auth');
