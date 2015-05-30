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

class Language extends Core
{
	public  $name = 'lang';

	public function __construct()
	{
		parent::__construct();

		//Liste des langues du site
		$nf_languages = $this->db	->select('code')
									->from('nf_settings_languages')
									->order_by('`order` ASC')
									->get();

		//Liste des langues acceptées par le client
		$user_languages = array();

		foreach (array_merge(array($this->session('language'), $this->user('language')), preg_replace('/^(.+);(.+)$/', '\1', explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']))) as $lang)
		{
			if (is_null($lang))
			{
				continue;
			}

			$user_languages[] = $lang;
			if (preg_match('/^(.+)[-_](.+)$/', $lang, $matches))
			{
				$user_languages[] = $matches[1];
			}
		}

		$user_languages = array_unique($user_languages);

		$languages = array_values(array_intersect($user_languages, $nf_languages));

		$this->config->update($this->config->site, !empty($languages) ? $languages[0] : $nf_languages[0]);

		setlocale(LC_ALL, $this('locale'));

		$this->user->check_http_authentification();
	}

	public function __invoke($name, $paths = array())
	{
		static $langs;
		
		if (empty($paths))
		{
			$paths = $this->load->paths['lang'];
		}
		
		if (!isset($langs[$checksum = md5(serialize($paths))]))
		{
			foreach ($paths as $path)
			{
				if (!file_exists($lang_path = $path.'/'.$this->config->lang.'.php'))
				{
					continue;
				}

				include $lang_path;
				
				$langs[$checksum] = $lang;

				if (isset($lang[$name]))
				{
					return $lang[$name];
				}
			}
		}
		else
		{
			return $langs[$checksum][$name];
		}
		
		$this->profiler->log('Traduction introuvable : '.$name, Profiler::WARNING);
		return '';
	}

	public function default_language(&$lang)
	{
		if ($lang == 'default')
		{
			$lang = $this->db	->select('code')
								->from('nf_settings_languages')
								->order_by('`order`')
								->row();
		}
	}
	
	public function parse($output)
	{
		return preg_replace('/{lang ([a-zA-Z0-9_.\/-]+)}/e', '$this(\'\1\');', $output);
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/core/language.php
*/