<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

//Camelcase to Underscored
function cc2u($string)
{
	return strtolower(preg_replace('/([^A-Z])([A-Z])/', '\\1_\\2', $string));
}

//Underscored to lower-camelcase
function u2lcc($string)
{
	$string = strtolower(preg_replace('/_+/', '_', trim($string, '_')));
	if (preg_match_all('/_(.?)/', $string, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER))
	{
		$count = 0;
		foreach ($matches as $match)
		{
			$string = substr_replace($string, strtoupper($match[1][0]), $match[0][1] - $count++, 2);
		}
	}
	
	return $string;
}

//Underscored to upper-camelcase
function u2ucc($string)
{
	return ucfirst(u2lcc($string));
}

function in_string($needle, $haystack, $strict = TRUE)
{
	if ($strict)
	{
		return strpos($haystack, $needle) !== FALSE;
	}
	else
	{
		return stripos($haystack, $needle) !== FALSE;
	}
}

function url_title($string)
{
	static $strings = array();
	
	if (isset($strings[$string]))
	{
		return $strings[$string];
	}
	
	static $a, $b;
	
	if ($a === NULL)
	{
		$chars = array(
			'a'  => 'ÀÁÂÃÄÅÆàáâãäå',
			'ae' => 'æ',
			'c'  => 'Çç',
			'e'  => 'ÈÉÊËèéêë',
			'i'  => 'ÌÍÎÏìíîï',
			'n'  => 'Ññ',
			'o'  => 'ÒÓÔÕÖòóôõö',
			'oe' => 'Œœ',
			'u'  => 'ÙÚÛÜùúûü',
			'y'  => 'Ýýÿ',
			'-'  => '_ '
		);

		$a = $b = array();
		foreach ($chars as $key => $value)
		{
			foreach (preg_split('/(?<!^)(?!$)/u', $value) as $char)
			{
				$a[] = $char;
				$b[] = $key;
			}
		}
	}
	
	return $strings[$string] = trim(preg_replace('/--+/', '-', preg_replace('/[^a-z0-9-]/', '', strtolower(str_replace($a, $b, strip_tags(utf8_html_entity_decode($string)))))), '-');
}

function strtolink($string)
{
	//TODO détecter les @Username et remplacer par le badge du membre...
	
	//regex by @diegoperini
	return nl2br(preg_replace_callback('_(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?_iuS', function($match){
		return '<a href="'.$match[0].'">'.str_shortener($match[0], 50).'</a>';
	}, $string));
}

function unique_id($list = array())
{
	do
	{
		$id = strtolower(substr(preg_replace('/[^A-Za-z0-9]/', '', base64_encode(str_repeat(sha1(uniqid('', TRUE), TRUE), 3))), 0, 32));
	}
	while ($list && in_array($id, $list));

	return $id;
}

function trim_word($string, $word)
{
	$word = implode('|', array_map(create_function('$a', 'return preg_quote($a, \'/\');'), array_offset_left(func_get_args())));

	return preg_replace('/^('.$word.')*(.*?)('.$word.')*$/', '\\2', $string);
}

function is_valid_email($email)
{
	if (function_exists('filter_var'))
	{
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}
	else
	{
		return preg_match('/.+?@.+?\.[a-z0-9]+/i', $email);
	}
}

function is_valid_url($url)
{
	if (function_exists('filter_var'))
	{
		return filter_var($url, FILTER_VALIDATE_URL);
	}
	else
	{
		return preg_match('#(https?)://.+?#', $url);
	}
}

function utf8_htmlentities($string, $flags = ENT_COMPAT)
{
	return htmlentities($string, $flags, 'UTF-8');
}

function utf8_html_entity_decode($string, $flags = ENT_COMPAT)
{
	return html_entity_decode($string, $flags, 'UTF-8');
}

function utf8_string($string)
{
	if (mb_detect_encoding($string, 'UTF-8', TRUE) != 'UTF-8')
	{
		$string = utf8_encode($string);
	}

	return $string;
}

function str_shortener($string, $max_length, $end = '&#8230;')
{
	if (strlen($string) <= $max_length)
	{
		return $string;
	}
	else
	{
		if (utf8_html_entity_decode($end) === $end)
		{
			$max_length -= strlen($end);
		}
	
		for ($i = $max_length; $i > 1; $i--)
		{
			if (in_array(substr($string, $i, 1), str_split(' .,;:!?-_"')) &&
				in_array(substr(url_title($string), $i - 1, 1), array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9))))
			{
				return substr($string, 0, $i).$end;
			}
		}
		
		return substr($string, 0, $max_length).$end;
	}
}

function bbcode($string)
{
	return NeoFrag::loader()->load->library('text_editor')->bbcode2html($string);
}

/*
NeoFrag Alpha 0.1
./neofrag/helpers/string.php
*/