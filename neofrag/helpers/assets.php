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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

function is_asset($extension = NULL)
{
	return in_array($extension ?: extension(NeoFrag::loader()->config->request_url), ['png', 'jpg', 'jpeg', 'gif', 'swf', 'css', 'js', 'eot', 'svg', 'ttf', 'woff', 'woff2', 'zip']);
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
		foreach (NeoFrag::loader()->paths['assets'] as $path)
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
				'lang' => NeoFrag::loader()->config->lang
			];

			$content = NeoFrag::loader()->template->parse($content, $data);
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
		}
		else
		{
			header('HTTP/1.1 200 OK');

			echo $content;
		}

		exit;
	}
}

function path($file, $file_type = '', $paths = [])
{
	if (func_num_args() == 1)
	{
		static $paths = [];
		
		if (!isset($paths[$file]))
		{
			$paths[$file] = NeoFrag::loader()->db->select('path')
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
			$paths = NeoFrag::loader()->paths['assets'];
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

/*
NeoFrag Alpha 0.1.4
./neofrag/helpers/assets.php
*/