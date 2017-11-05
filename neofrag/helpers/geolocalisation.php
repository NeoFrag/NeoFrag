<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

function geolocalisation($address_ip)
{
	NeoFrag()->js('neofrag.geolocalisation');
	return '<img src="'.image('ajax-loader.gif').'" style="margin-right: 10px;" data-geolocalisation="'.$address_ip.'" alt="" />';
}
