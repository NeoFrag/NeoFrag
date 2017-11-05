<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

function is_asset($extension = NULL)
{
	return in_array($extension ?: extension($_SERVER['REQUEST_URI']), ['png', 'jpg', 'jpeg', 'gif', 'swf', 'css', 'js', 'eot', 'svg', 'ttf', 'woff', 'woff2', 'zip']);
}

function icon($icon)
{
	if (preg_match('/^fa-(.+)/', $icon, $match))
	{
		return '<i class="fa fa-fw fa-'.$match[1].'"></i>';
	}
	
	return '';
}

function asset($file_path, $file_name = '')
{
	if (check_file($file_path))
	{
		$content = file_get_contents($file_path);
		$date    = filemtime($file_path);
	}

	if (!isset($content))
	{
		foreach (NeoFrag()->paths('assets') as $path)
		{
			if (!check_file($path = $path.'/'.$file_path))
			{
				continue;
			}

			$content = file_get_contents($path);
			$date    = filemtime($path);
			
			break;
		}
	}

	if (isset($content))
	{
		if (in_array($ext = extension($file_path), ['css', 'js']))
		{
			$data = [
				'lang' => NeoFrag()->config->lang
			];

			$content = NeoFrag()->view->content($content, $data);
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
}

function path($file, $file_type = '', $paths = [])
{
	if (func_num_args() == 1)
	{
		static $paths = [];
		
		if (!isset($paths[$file]))
		{
			$paths[$file] = NeoFrag()->db->select('path')
												->from('nf_files')
												->where('file_id', $file)
												->row();
		}
		
		return $paths[$file] ? url($paths[$file]) : '';
	}
	else
	{
		if (is_valid_url($file))
		{
			return $file;
		}

		if (!$paths)
		{
			$loader = NeoFrag()->theme ? NeoFrag()->theme->load : NeoFrag();
			$paths = $loader->paths('assets');
		}

		if (!in_array($file_type, ['images', 'css', 'js']))
		{
			return url($file_type.'/'.$file);
		}

		//json_encode backslashe les /
		$file = str_replace('\/', '/', $file);

		static $assets;
		
		if (!isset($assets[$checksum = md5(serialize($paths))][$file_type][$file]))
		{
			foreach ($paths as $path)
			{
				if (check_file($file_path = $path.'/'.$file_type.'/'.$file))
				{
					return $assets[$checksum][$file_type][$file] = url($file_path);
				}
			}
		}
		else
		{
			return $assets[$checksum][$file_type][$file];
		}

		if (check_file($file))
		{
			return url($file);
		}

		return url($file_type.'/'.$file);
	}
}

function image($file)
{
	return path($file, 'images');
}

function css($file)
{
	return path($file, 'css');
}

function js($file)
{
	return path($file, 'js');
}
