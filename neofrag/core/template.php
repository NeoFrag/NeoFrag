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

class Template extends Core
{
	private $_functions = 'url_title(*)|bbcode(*)|strtolink(*)|time_span(*)|strtoseconds(*)';//|trim(*)|trim(*,*)';

	private function _parse_double($content, &$data, $paths, $prefix = '')
	{
		if (preg_match_all('#{'.$prefix.'([^}]+)}([^{]*){/'.$prefix.'\1}#s', $content, $matches, PREG_SET_ORDER))
		{
			foreach ($matches as $vars)
			{
				list($source, $var, $code) = $vars;

				if (isset($data[$var]) && is_array($data[$var]))
				{
					$result = '';
					foreach ($data[$var] as $row)
					{
						$result .= $this->parse($code, $row, $paths, $var.'.');
					}

					$content = str_replace($source, $result, $content);

					unset($data[$var]);
				}
				else
				{
					$content = str_replace($source, '', $content);
					$this->profiler->log('Variable inexistante : '.$var, Profiler::WARNING);
				}
			}
		}

		return $content;
	}

	private function _parse_single($content, $data)
	{
		foreach (array_keys($data) as $var)
		{
			$content = str_replace('{'.$var.'}', '<?php echo (isset($data[\''.$var.'\'])) ? $data[\''.$var.'\'] : \'\'; ?>', $content);
		}

		return $content;
	}

	private function _parse_functions($content, $data)
	{
		if (preg_match_all('#{(.+?)}#', $content, $matches, PREG_SET_ORDER))
		{
			$functions = str_replace(array('(', ')', '*', ','), array('\(', '\)', '([A-Za-z0-9_./-]*?)', ', ?'), $this->_functions);

			foreach ($matches as $vars)
			{
				if (preg_match('#'.$functions.'#', $vars[1], $results))
				{
					$func = explode('(', $vars[1])[0];

					$args = array();
					foreach (array_offset_left($results) as $var)
					{
						if ($var)
						{
							$args[] = '$data[\''.$var.'\']';
						}
					}

					$content = str_replace($vars[0], '<?php echo '.$func.'('.implode(', ', $args).'); ?>', $content);
				}
			}
		}

		return $content;
	}

	public function parse($content, $data = array(), $loader = NULL, $parse_php = TRUE)
	{
		if (!$loader)
		{
			$loader = $this->load;
		}

		if ($parse_php)
		{
			$content = $this->_parse($content, $data);
		}

		//Si le template contient du code PHP
		if (in_string('<?php', $content) && in_string('?>', $content))
		{
			$NeoFrag = $this->load;
			$paths   = $loader->paths;

			//Récupèration du contenu du template avec exécution du code PHP
			$content = eval('ob_start(); ?>'.preg_replace('/;*\s*\?>/', '; ?>', str_replace('<?=', '<?php echo ', $content)).'<?php return ob_get_clean();');

			/*
			file_put_contents($file = 'tmp'.microtime().'.php', $content);

			ob_start();

			include $file;

			$content = ob_get_clean();*/
		}

		return $content;
	}

	public function clean($content)
	{
		return preg_replace('#{/?[a-z][a-z0-9_.-]*? ?([a-zA-Z0-9_./-]+?(, ?[a-zA-Z0-9_./-]+?)*?)?}#', '', $content);
	}

	private function _parse($content, $data)
	{
		//$this->profiler->log('Analyse du template', Profiler::INFO);

		if (is_callable($content))
		{
			$content = call_user_func($content, $data);
		}
		
		//$content = $this->_parse_double($content, $data, $paths, $prefix);
		$content = $this->_parse_single($content, $data);
		$content = $this->_parse_functions($content, $data);

		$content = preg_replace('/{config ([a-z0-9_]+)}/',                       '<?php echo $NeoFrag->config->\1; ?>', $content);
		$content = preg_replace('/{user ([a-z_]+)}/',                            '<?php echo $NeoFrag->user(\'\1\'); ?>', $content);
		$content = preg_replace('/{fa-icon ([a-z-]+)}/',                         '<?php echo $NeoFrag->assets->icon(\'fa-\1\'); ?>', $content);
		$content = preg_replace('/{(image|css|js|swf) ([0-9]+)}/',               '<?php echo $NeoFrag->assets->file(\'\2\'); ?>', $content);
		$content = preg_replace('#{(image|css|js|swf) ([a-zA-Z0-9_.\\\/:-]+)}#', '<?php echo $NeoFrag->assets->load(\'\2\', \'\1\', $paths[\'assets\'], (isset($data[\'base_url\'])) ? $data[\'base_url\'] : \'\'); ?>', $content);
		$content = preg_replace('#{lang ([a-zA-Z0-9_.\\\/-]+)}#',                '<?php echo $NeoFrag->lang(\'\1\', $paths[\'lang\']); ?>', $content);
		$content = preg_replace('#{view ([a-zA-Z0-9_.\\\/-]+)}#',                '<?php echo $loader->load->view(\'\1\', $data); ?>', $content);
		$content = preg_replace('#{zone ([0-9]+)}(.+?){zone}(.+?){/zone \1}#s',  '<?php if ($zone\1 = $NeoFrag->output->display_zone(\1)): ?>\2<?php echo $zone\1; ?>\3<?php endif; ?>', $content);
		$content = preg_replace('/{zone ([0-9]+)}/',                             '<?php echo $NeoFrag->output->display_zone(\1); ?>', $content);

		//Correction des codes php imbriqués
		while (preg_match($patern = '/echo ((?:[^?]|\?[^>])*?)<\?php echo (.*?); \?>(.*?);/', $content))
		{
			$content = preg_replace($patern, 'echo \1\'.\2.\'\3;', $content);
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
		if (($cache = $this->cache($file_path, filemtime($file_path), 'tpl')) === FALSE)
		{
			$cache = $this->_parse(file_get_contents($file_path), $data);
			$this->cache->set($file_path, $cache, filemtime($file_path), 'tpl');
		}

		return $this->parse($cache, $data, $loader, FALSE);
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/core/template.php
*/