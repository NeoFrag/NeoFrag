<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

function array_last_key($array)
{
	$keys = array_keys($array);

	return end($keys);
}

function array_last($array)
{
	return end($array);
}

function array_offset_left($array, $offset = 1)
{
	return array_slice($array, $offset);
}

function array_offset_right($array, $length = 1)
{
	return array_slice($array, 0, -$length);
}

function array_natsort(&$array, $data = NULL)
{
	uasort($array, function($a, $b) use ($data){
		return str_nat($a, $b, $data);
	});
}
