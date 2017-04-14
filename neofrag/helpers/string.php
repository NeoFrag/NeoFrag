<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

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
	$string = (string)$string;

	static $strings = [];

	if (isset($strings[$string]))
	{
		return $strings[$string];
	}

	return $strings[$string] = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower(transliterator_transliterate('Any-Latin; Latin-ASCII; [\u0080-\u7fff] remove', strip_tags(utf8_html_entity_decode($string))))), '-');
}

function str_nat($a, $b, $data = NULL)
{
	if ($data === NULL || !is_callable($data))
	{
		$data = function($a){
			return $a;
		};
	}

	return strnatcasecmp(url_title($data($a)), url_title($data($b)));
}

function escape_html_tags($string, $callback)
{
	$offset = 0;
	$string = '>'.$string;

	while ($offset < strlen($string) && preg_match('_>([^<]+(</)?)_', $string, $match, PREG_OFFSET_CAPTURE, $offset))
	{
		$offset = $match[1][1];

		if (!isset($match[2]))
		{
			$replacement = $callback($match[1][0]);
			$string      = substr_replace($string, $replacement, $offset, strlen($match[1][0]));
			$offset     += strlen($replacement);
		}
		else
		{
			$offset += strlen($match[1][0]);
		}
	}

	return substr($string, 1);
}

function strtolink($string, $is_html = FALSE)
{
	if ($is_html)
	{
		return escape_html_tags($string, 'strtolink');
	}

	//regex by @diegoperini
	$string = preg_replace_callback('_(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?_iuS', function($match){
		return '<a href="'.$match[0].'">'.str_shortener($match[0], 50).'</a>';
	}, $string);

	return preg_replace_callback('_@((?:&quot;(.+?)&quot;)|([^@\s]+))_', function($match){
		static $users;

		if ($users === NULL)
		{
			foreach (NeoFrag()->db->select('id', 'username')->from('nf_user')->where('deleted', FALSE)->get() as $user)
			{
				$users[$user['id']] = $user['username'];
			}
		}

		$username = !empty($match[3]) ? $match[3] : $match[2];

		return ($user_id = array_search($username, $users)) !== FALSE ? NeoFrag()->user->link($user_id, $username, '@') : $match[0];
	}, $string);
}

function unique_id($list = [])
{
	do
	{
		$id = strtolower(substr(preg_replace('/[^A-Za-z0-9]/', '', base64_encode(str_repeat(sha1(uniqid('', TRUE), TRUE), 3))), 0, 32));
	}
	while ($list && in_array($id, $list));

	return $id;
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
	if (($encoding = mb_detect_encoding($string, 'auto', TRUE)) != 'UTF-8')
	{
		$string = mb_convert_encoding($string, 'UTF-8', $encoding ?: 'ASCII');
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
	return nl2br(strtolink(NeoFrag()->bbcode->bbcode2html($string), TRUE));
}

function highlight($string, $keywords, $max_length = 256)
{
	$string = nl2br(preg_replace($patern = '/'.implode('|', array_map(function($a){ return preg_quote($a, '/'); }, $keywords)).'/i', '<mark>\0</mark>', htmlspecialchars(utf8_html_entity_decode(strip_tags(bbcode($string))), ENT_COMPAT, 'UTF-8')));

	return str_shortener(substr($string, strpos($string, '<mark>')), $max_length);
}

function version_format($version)
{
	return strtolower(trim(preg_replace('/[^\d.]/', '', $version), '.'));
}
