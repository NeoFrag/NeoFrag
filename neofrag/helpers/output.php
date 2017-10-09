<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

//TODO
function display($objects, $id = NULL)
{
	$output = '';

	if (!NeoFrag()->url->ajax)
	{
		if (is_object($objects) && is_a($objects, 'Panel'))
		{
			$objects = NeoFrag()->col($objects);
		}

		if (is_object($objects) && is_a($objects, 'Col'))
		{
			$objects = NeoFrag()->row($objects);
		}
	}

	if (!is_array($objects))
	{
		$objects = [$objects];
	}

	foreach ($objects as $i => $object)
	{
		if ($id !== NULL && method_exists($object, 'id'))
		{
			$object->id($i);
		}

		$output .= $object;
	}

	return $output;
}
