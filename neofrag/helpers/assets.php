<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

function is_asset($extension = NULL)
{
	return !in_array($extension ?: extension($_SERVER['REQUEST_URI']), ['', 'php', 'json', 'txt', 'xml']);
}

function icon($icon)
{
	if (preg_match('/^(fa[bsrld] fa-.+)/', $icon, $match))
	{
		return '<i class="icon '.$match[0].' fa-fw"></i>';
	}
	else if (preg_match('/^pe-7s-.+/', $icon, $match))
	{
		return '<i class="icon '.$match[0].'"></i>';
	}

	return '<i class="icon">'.$icon.'</i>';
}

function path($file, $file_type = '', $caller = NULL)
{
	if (is_valid_url($file))
	{
		return $file;
	}

	if (!in_array($file_type, ['images', 'css', 'js', 'fonts']))
	{
		return url($file_type.'/'.$file);
	}

	if (!$caller)
	{
		$caller = Neofrag()->output->theme() ?: Neofrag();
	}

	if ($path = $caller->__path('assets', $file_type.'/'.$file))
	{
		return url($path);
	}
	else if (check_file($file))
	{
		return url($file);
	}

	return url($file_type.'/'.$file);
}

function image($file, $caller = NULL)
{
	return path($file, 'images', $caller);
}

function css($file, $caller = NULL)
{
	return path($file, 'css', $caller);
}

function js($file, $caller = NULL)
{
	return path($file, 'js', $caller);
}
