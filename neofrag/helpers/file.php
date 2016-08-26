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

function relative_path($file)
{
	$file  = substr(str_replace('\\', '/', $file), strlen(NEOFRAG_CMS));

	return (substr($file, 0, 1) == '/' ? '.' : './').$file;
}

function extension($file, &$path = NULL)
{
	$url = parse_url($file);
	return strtolower(pathinfo($path = $url['path'], PATHINFO_EXTENSION));
}

function get_mime_by_extension($extension)
{
	$mimes = [
		'bmp'   => 'image/bmp',
		'css'   => 'text/css',
		'eot'   => 'application/vnd.ms-fontobject"',
		'gif'   => 'image/gif',
		'jpeg'  => 'image/jpeg',
		'jpg'   => 'image/jpeg',
		'js'    => 'application/x-javascript',
		'json'  => 'application/json',
		'html'  => 'text/html',
		'otf'   => 'application/x-font-opentype',
		'png'   => 'image/png',
		'svg'   => 'image/svg+xml',
		'swf'   => 'application/x-shockwave-flash',
		'ttf'   => 'application/x-font-ttf',
		'woff'  => 'application/x-font-woff',
		'woff2' => 'application/font-woff2',
		'zip'   => 'application/zip'
	];

	return $mimes[$extension];
}

function file_upload_max_size()
{
	static $max_size = -1;

	if ($max_size < 0)
	{
		$max_size = parse_size(ini_get('post_max_size'));

		$upload_max = parse_size(ini_get('upload_max_filesize'));
		
		if ($upload_max > 0 && $upload_max < $max_size)
		{
			$max_size = $upload_max;
		}
	}
	
	return $max_size;
}

function parse_size($size)
{
	$unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
	$size = preg_replace('/[^0-9\.]/', '', $size);
	
	if ($unit)
	{
		return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
	}
	else
	{
		return round($size);
	}
}

function image_resize($filename, $width, $height = NULL)
{
	$info = getimagesize($filename);
	$w    = $info[0];
	$h    = $info[1];
	$type = $info[2];
	$mime = $info['mime'];
	
	if ($height === NULL)
	{
		$height = ceil($h * $width / $w);
	}
	
	if ($w <= $width && $h <= $height)
	{
		return;
	}
	
	$resize = imagecreatetruecolor($width, $height);
	
	if ($mime == 'image/png')
	{
		$image = imagecreatefrompng($filename);
	}
	else if ($mime == 'image/jpeg')
	{
		$image = imagecreatefromjpeg($filename);
	}
	else if ($mime == 'image/gif')
	{
		$image = imagecreatefromgif($filename);
	}
	else
	{
		return;
	}
	
	if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_PNG)
	{
		$current_transparent = imagecolortransparent($image);
		if ($current_transparent != -1)
		{
			$transparent_color = imagecolorsforindex($image, $current_transparent);
			$current_transparent = imagecolorallocate($resize, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
			imagefill($resize, 0, 0, $current_transparent);
			imagecolortransparent($resize, $current_transparent);
		}
		else if ($type == IMAGETYPE_PNG)
		{
			imagealphablending($resize, FALSE);
			imagefill($resize, 0, 0, imagecolorallocatealpha($resize, 0, 0, 0, 127));
			imagesavealpha($resize, TRUE);
		}
	}
	
	imagecopyresampled($resize, $image, 0, 0, 0, 0, $width, $height, $w, $h);
	
	if ($mime == 'image/png')
	{
		imagepng($resize, $filename);
	}
	else if ($mime == 'image/jpeg')
	{
		imagejpeg($resize, $filename, 100);
	}
	else if ($mime == 'image/gif')
	{
		imagegif($resize, $filename);
	}
}

function rmdir_all($directory)
{
	foreach (scandir($directory = rtrim($directory, '/')) as $dir)
	{
		if (!in_array($dir, ['.', '..']))
		{
			if (is_dir($dir = $directory.'/'.$dir))
			{
				rmdir_all($dir);
			}
			else
			{
				unlink($dir);
			}
		}
	}
	
	rmdir($directory);
}

function copy_all($src, $dst)
{ 
	if (!file_exists($dst))
	{
		mkdir($dst, 0777, TRUE);
	}

	$dir = opendir($src); 

	while (($file = readdir($dir)) !== FALSE)
	{
		if (!in_array($file, ['.', '..']))
		{
			if (is_dir($src.'/'.$file))
			{
				copy_all($src.'/'.$file, $dst.'/'.$file); 
			}
			else
			{
				copy($src.'/'.$file, $dst.'/'.$file); 
			}
		}
	}
	
	closedir($dir); 
}

/*
NeoFrag Alpha 0.1.4
./neofrag/helpers/file.php
*/