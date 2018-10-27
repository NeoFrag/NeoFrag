<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

function url($url = '')
{
	return NeoFrag()->url($url);
}

function redirect($location = '')
{
	return NeoFrag()->url->redirect(url($location));
}

function redirect_back($default = '')
{
	return redirect(NeoFrag()->url->back() ?: $default);
}

function refresh()
{
	return NeoFrag()->url->refresh();
}

function urltolink($url)
{
	return '<a href="'.$url.'">'.parse_url($url, PHP_URL_HOST).'</a>';
}
