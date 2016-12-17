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

function network_get($url, $file = NULL, $callback = NULL)
{
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);

	if (isset($_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']))
	{
		curl_setopt($ch, CURLOPT_REFERER, (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	}

	if ($file === NULL)
	{
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$result = curl_exec($ch);

		if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200)
		{
			$result = FALSE;
		}
	}
	else
	{
		$f = fopen($file, 'w+b');

		curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data) use ($f, $callback){
			$bytes = fwrite($f, $data);

			if (is_callable($callback))
			{
				static $size = 0;
				static $total;
				
				if ($total === NULL)
				{
					$total = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
				}
				
				$callback($size += $bytes, $total);
			}

			return $bytes;
		});

		curl_exec($ch);

		fclose($f);

		$result = NULL;
	}

	curl_close($ch);

	return $result;
}

/*
NeoFrag Alpha 0.1.5
./neofrag/helpers/network.php
*/