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

class Assets extends Core
{
	public function is_asset()
	{
		static $is_asset;
		
		if (is_null($is_asset))
		{
			$is_asset = in_array(extension($this->config->request_url, $path), array('png', 'jpg', 'jpeg', 'gif', 'swf', 'css', 'js', 'eot', 'svg', 'ttf', 'woff', 'woff2', 'zip'));
		}
		
		return $is_asset;
	}
	
	public function file($file_id, $file = '', $path = '')
	{
		if ($path && (!$file || file_exists($path)))
		{
			return $this->config->base_url.$path;
		}
		else
		{
			static $paths = array();
			
			if (!isset($paths[$file_id]))
			{
				$paths[$file_id] = $this->db->select('path')
											->from('nf_files')
											->where('file_id', $file_id)
											->row();
			}
		
			return $this->config->base_url.$paths[$file_id];
		}
	}
	
	public function load($file_name, $file_type, $paths, $base_url = '')
	{
		if (is_valid_url($file_name))
		{
			return $file_name;
		}

		if (!$base_url)
		{
			$base_url = $this->config->base_url;
		}
	
		if (!in_array($file_type, array('image', 'css', 'js', 'swf')))
		{
			$this->profiler->log('Type de ressource non géré', Profiler::WARNING);
			return $base_url.$file_type.'/'.$file_name;
		}

		if ($file_type == 'image')
		{
			$file_type .= 's';
		}

		//json_encode backslashe les /
		$file_name = str_replace('\/', '/', $file_name);

		static $assets;
		
		if (!isset($assets[$base_url][$checksum = md5(serialize($paths))][$file_type][$file_name]))
		{
			foreach ($paths as $path)
			{
				if (file_exists($file_path = $path.'/'.$file_type.'/'.$file_name))
				{
					return $assets[$base_url][$checksum][$file_type][$file_name] = $base_url.trim_word($file_path, './');
				}
			}
		}
		else
		{
			return $assets[$base_url][$checksum][$file_type][$file_name];
		}

		if (file_exists($file_name))
		{
			return $this->config->base_url.$file_name;
		}

		return $base_url.$file_type.'/'.$file_name;
	}

	public function __invoke($file_path)
	{
		if (file_exists($file_path))
		{
			$content = file_get_contents($file_path);
			$date    = filemtime($file_path);
		}

		if (!isset($content))
		{
			foreach ($this->load->paths['assets'] as $path)
			{
				if (!file_exists($path = $path.'/'.$file_path))
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
			if (in_array($ext = extension($file_path), array('css', 'js')))
			{
				$data = array(
					'base_url' => $this->config->base_url,
					'lang'     => $this->config->lang
				);

				$content = $this->template->parse($content, $data);
			}
			
			ob_end_clean();

			header('Last-Modified: '.date('r', $date));
			header('Etag: '.($etag = md5($content)));
			header('Content-Type: '.get_mime_by_extension($ext));
			
			if ($ext == 'zip')
			{
				header('Content-Disposition: attachment; filename="'.basename($file_path).'"');
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

	public function icon($icon)
	{
		if (preg_match('/^fa-(.+)/', $icon, $match))
		{
			return '<i class="fa fa-'.$match[1].'"></i>';
		}
		
		return '';
	}
}

/*
NeoFrag Alpha 0.1.1
./neofrag/core/assets.php
*/