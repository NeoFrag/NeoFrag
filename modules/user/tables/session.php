<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

$this	->col(function($session){
			return user_agent($session->data->session->user_agent);
		})
		->col('Adresse IP', function($session){
			return geolocalisation($ip_address = $session->data->session->ip_address).'<span data-toggle="tooltip" data-original-title="'.$session->data->session->host_name.'">'.$ip_address.'</span>';
		})
		->col('Site référent', function($session){
			return ($referer = $session->data->session->referer) ? urltolink($referer) : $this->lang('Aucun');
		})
		->col('Date', 'last_activity');
