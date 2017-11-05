<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

function post($var = NULL)
{
	if ($var === NULL)
	{
		return $_POST;
	}

	if (isset($_POST[$var]))
	{
		return $_POST[$var];
	}

	return NULL;
}

function post_check($args, $post = NULL)
{
	if (is_array($args))
	{
		if ($post === NULL)
		{
			$post = post();
		}
		else if (!is_array($post))
		{
			$post = post($post);
		}
	}
	else
	{
		$args = func_get_args();
		$post = post();
	}

	$data = [];

	foreach ($args as $var)
	{
		if (isset($post[$var]))
		{
			$data[$var] = $post[$var];
		}
		else
		{
			return FALSE;
		}
	}

	return $data;
}
