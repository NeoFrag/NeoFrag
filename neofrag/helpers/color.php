<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

function get_colors()
{
	return [
		'primary'   => '#007bff',
		'secondary' => '#6c757d',
		'success'   => '#28a745',
		'danger'    => '#dc3545',
		'warning'   => '#ffc107',
		'info'      => '#17a2b8',
		'light'     => '#f8f9fa',
		'dark'      => '#343a40'
	];
}

function color2hex($color)
{
	$colors = get_colors();
	return isset($colors[$color]) ? $colors[$color] : $color;
}
