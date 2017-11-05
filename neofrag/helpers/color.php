<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

function get_colors()
{
	return [
		'default' => '#777777',
		'primary' => '#337ab7',
		'success' => '#5cb85c',
		'info'    => '#5bc0de',
		'warning' => '#f0ad4e',
		'danger'  => '#d9534f'
	];
}

function color2hex($color)
{
	$colors = get_colors();
	return isset($colors[$color]) ? $colors[$color] : $color;
}
