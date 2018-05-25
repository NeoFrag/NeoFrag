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
	if (preg_match('/^fa-(.+)/', $icon, $match))
	{
		return '<i class="fa fa-fw fa-'.$match[1].'"></i>';
	}
	else if (preg_match('/^pe-7s-.+/', $icon, $match))
	{
		return '<i class="'.$match[0].'"></i>';
	}

	return '<i class="fa">'.$icon.'</i>';
}

function asset($file_path, $file_name = '')
{
	if (check_file($file_path))
	{
		$content = file_get_contents($file_path);
		$date    = filemtime($file_path);

		if (in_array($ext = extension($file_path), ['css', 'js']))
		{
			$content = NeoFrag()->view->content($content);
		}

		ob_end_clean();

		header('Last-Modified: '.date('r', $date));
		header('Etag: '.($etag = md5($content)));
		header('Content-Type: '.get_mime_by_extension($ext));

		if ($ext == 'zip')
		{
			header('Content-Disposition: attachment; filename="'.basename($file_name ?: $file_path).'"');
		}

		if ((isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $date) || (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag))
		{
			header('HTTP/1.1 304 Not Modified');
			exit;
		}
		else
		{
			header('HTTP/1.1 200 OK');
			header('Content-Length: '.strlen($content));
			exit($content);
		}
	}
	else if (NEOFRAG_DEBUG_BAR || NEOFRAG_LOGS)
	{
		NeoFrag()->debug('INFO', 'ASSET', 'Not exists on disk');
	}
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

function css($file)
{
	return path($file, 'css');
}

function js($file)
{
	return path($file, 'js');
}
