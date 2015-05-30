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

class Cache extends Core
{
	public function __invoke($name, $date, $ext)
	{
		if (file_exists($file = './cache/'.md5($name).'.'.$ext.'.php'))
		{
			list($file_date, $cache) = $this->_get_dates($file);

			if (filemtime($file) <= $cache && $date <= $file_date)
			{
				return file_get_contents($file);
			}
		}

		return FALSE;
	}

	public function set($name, $content, $date, $ext)
	{
		if (!file_exists('./cache'))
		{
			mkdir('./cache');
		}
		
		file_put_contents('./cache/'.md5($name).'.'.$ext.'.php', '<?php /* '.$date.'.'.time().' */ ?>'."\r\n".$content);
	}

	private function _get_dates($path)
	{
		if (preg_match('#^<\?php /\* ([0-9]+).([0-9]+) \*/ \?>#', file($path)[0], $matches))
		{
			return array((int)$matches[1], (int)$matches[2]);
		}

		return 0;
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/core/cache.php
*/