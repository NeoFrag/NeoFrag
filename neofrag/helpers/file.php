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

function relative_path($file)
{
	$file  = substr(str_replace('\\', '/', $file), strlen(NEOFRAG_CMS));

	return ((substr($file, 0, 1) == '/') ? '.' : './').$file;
}

function extension($file, &$path = NULL)
{
	$url = parse_url($file);
	return strtolower(pathinfo($path = $url['path'], PATHINFO_EXTENSION));
}

function get_mime_by_extension($extension)
{
	$mimes = array(
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
	);

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

function image_resize($filename, $width, $height)
{
	$info = getimagesize($filename);
	$w    = $info[0];
	$h    = $info[1];
	$mime = $info['mime'];
	
	if ($w == $width && $h == $height)
	{
		return;
	}
	
	$resize = imagecreatetruecolor($width, $width);
	
	if ($mime == 'image/png')
	{
		$image = imagecreatefrompng($filename);
		imagealphablending($resize, FALSE);
		imagesavealpha($resize, TRUE);
		imagecolortransparent($resize, imagecolorallocatealpha($resize, 255, 255, 255, 127));
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
	
	imagecopyresampled($resize, $image, 0, 0, 0, 0, $width, $width, $w, $h);
	
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

/*
NeoFrag Alpha 0.1
./neofrag/helpers/file.php
*/