<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

function url($url = '')
{
	if (substr($url, 0, 1) == '#')
	{
		$url = NeoFrag()->url->request.$url;
	}

	return NeoFrag()->url->base.$url;
}

function redirect($location = '')
{
	header('Location: '.url($location));
	exit;
}

function redirect_back($default = '')
{
	header('Location: '.url(NeoFrag()->session->get_back() ?: $default));
	exit;
}

function refresh()
{
	header('Location: '.$_SERVER['REQUEST_URI']);
	exit;
}

function urltolink($url)
{
	return '<a href="'.$url.'">'.parse_url($url, PHP_URL_HOST).'</a>';
}
