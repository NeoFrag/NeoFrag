<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

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

function output($type)
{
	if (in_array($type, ['css', 'js', 'js_load']) && !empty(NeoFrag()->{$type}))
	{
		if ($type == 'css')
		{
			if ($v = (int)NeoFrag()->config->nf_version_css)
			{
				$v = '?v='.$v;
			}

			$output = array_map(function($a) use ($v){
				return '<link rel="stylesheet" href="'.path($a[0].'.css', 'css', $a[2]->paths('assets')).($v ?: '').'" type="text/css" media="'.$a[1].'" />';
			}, NeoFrag()->css);
		}
		else if ($type == 'js')
		{
			$output = array_map(function($a){
				return '<script type="text/javascript" src="'.path($a[0].'.js', 'js', $a[1]->paths('assets')).'"></script>';
			}, NeoFrag()->js);
		}
		else if ($type == 'js_load')
		{
			$output = NeoFrag()->js_load;
		}

		return implode("\r\n", array_unique($output));
	}
}
