<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

function get_colors($name = NULL, $convert = TRUE)
{
	$colors = [
		'primary'   => '#007bff',
		'secondary' => '#6c757d',
		'success'   => '#28a745',
		'danger'    => '#dc3545',
		'warning'   => '#ffc107',
		'info'      => '#17a2b8',
		'light'     => '#f8f9fa',
		'dark'      => '#343a40',
		'link'      => ''
	];

	if ($name === NULL)
	{
		return array_filter($colors);
	}
	else if (!is_empty($name))
	{
		list($color) = explode(' ', $name, 2);

		if (array_key_exists($color, $colors))
		{
			return $convert ? $colors[$color] : $name;
		}
		else if ($convert && preg_match('/^#([a-f0-9]{3}){1,2}/i', $name))
		{
			return $name;
		}

		trigger_error('Invalid color: '.$name, E_USER_WARNING);
	}
}
