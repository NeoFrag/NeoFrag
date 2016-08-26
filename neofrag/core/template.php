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

class Template extends Core
{
	public function parse($content, $data = [], $loader = NULL, $parse_php = TRUE)
	{
		if (!$loader)
		{
			$loader = $this->load;
		}

		if ($parse_php && is_callable($content))
		{
			$content = call_user_func($content, $data, $loader);
		}
		//Si le template contient du code PHP
		else if (in_string('<?php', $content) && in_string('?>', $content))
		{
			$NeoFrag = $this->load;
			$paths   = $loader->paths;
			
			$global = isset($GLOBALS['loader']) ? $GLOBALS['loader'] : NULL;
			$GLOBALS['loader'] = $loader;

			//Récupèration du contenu du template avec exécution du code PHP
			$content = eval('ob_start(); ?>'.preg_replace('/;*\s*\?>/', '; ?>', str_replace('<?=', '<?php echo ', $content)).'<?php return ob_get_clean();');

			$GLOBALS['loader'] = $global;
		}

		return $content;
	}

	public function parse_data(&$data, $loader)
	{
		foreach ($data as &$var)
		{
			if (is_array($var))
			{
				$this->parse_data($var, $loader);
			}
			else
			{
				$var = $this->parse($var, $data, $loader);
			}
		}
	}

	public function load($file_path, $data, $loader)
	{
		return $this->parse(file_get_contents($file_path), $data, $loader, FALSE);
	}
}

/*
NeoFrag Alpha 0.1.4
./neofrag/core/template.php
*/