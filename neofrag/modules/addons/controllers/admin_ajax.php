<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_addons_c_admin_ajax extends Controller_Module
{
	public function index()
	{
		if ($this->has_method($addon = '_'.post('addon').'_list'))
		{
			return $this->col($this->$addon()->size('col-md-8 col-lg-9'));
		}
	}

	public function active($type, $object)
	{
		$this->extension('json');

		if ($type == 'authenticator')
		{
			if (!$object->is_setup())
			{
				return [
					'danger' => 'Vous devez configurer l\'authentificateur'
				];
			}

			$table      = 'nf_settings_authenticators';
			$is_enabled = !$object->is_enabled();
			$title      = 'L\'authentificateur '.$object->title;
		}
		else
		{
			$table      = 'nf_settings_addons';
			$is_enabled = !$this->db->select('is_enabled')
									->from('nf_settings_addons')
									->where('name', $object->name)
									->where('type', $type)
									->row();

			$this->db->where('type', $type);

			$title = 'Le '.$type.' '.$object->get_title();
		}

		$this->db	->where('name', $object->name)
					->update($table, [
						'is_enabled' => $is_enabled
					]);

		return [
			'success' => $title.' est '.($is_enabled ? 'activé' : 'désactivé')
		];
	}
	
	public function install()
	{
		$this->extension('json');
		
		if (!empty($_FILES['file']) && extension($_FILES['file']['name']) == 'zip')
		{
			if ($zip = zip_open($_FILES['file']['tmp_name']))
			{
				while (file_exists($tmp = sys_get_temp_dir().'/'.unique_id()));
				
				dir_create($tmp);

				while ($zip_entry = zip_read($zip))
				{
					$entry_name = zip_entry_name($zip_entry);

					if (substr($entry_name, -1) == '/')
					{
						dir_create($tmp.'/'.$entry_name);
					}
					else if (zip_entry_open($zip, $zip_entry, 'r'))
					{
						file_put_contents($tmp.'/'.$entry_name, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
					}

					zip_entry_close($zip_entry);
				}

				zip_close($zip);
				
				$folders = array_filter(scandir($tmp), function($a) use ($tmp){
					return !in_array($a, ['.', '..']) && is_dir($tmp.'/'.$a);
				});

				$install_addon = function ($dir, $types = NULL) {
					if ($types === NULL)
					{
						$types = ['Module', 'Widget', 'Theme'];
					}
					else if (!is_array($types))
					{
						$types = (array)$types;
					}
					
					foreach (scandir($dir) as $filename)
					{
						if (!is_dir($file = $dir.'/'.$filename) &&
							preg_match('/^(.+?)\.php$/', $filename, $match) &&
							preg_match('/class ('.implode('|', array_map(function($a){ return strtolower(substr($a, 0, 1)); }, $types)).')_('.$match[1].') extends ('.implode('|', $types).')/', $content = php_strip_whitespace($file), $match) &&
							$match[1] == strtolower(substr($match[3], 0, 1)))
						{
							foreach (['version', 'nf_version'] as $var)
							{
								$$var = preg_match('/\$'.$var.'[ \t]*?=[ \t]*?([\'"])(.+?)\1;/', $content, $match2) ? version_format($match2[2]) : NULL;
							}
							
							if (!empty($version) && !empty($nf_version))
							{
								$type = strtolower($match[3]);

								$addon = NeoFrag()->$type($name = strtolower($match[2]), TRUE);

								if ($addon)
								{
									$update = TRUE;
									
									if (($cmp = version_compare($version, version_format($addon->version))) === 0)
									{
										return [
											'warning' => 'Le '.$type.' '.$addon->get_title().' est déjà installé en version '.$version
										];
									}
									else if ($cmp === -1)
									{
										return [
											'danger' => 'Le '.$type.' '.$addon->get_title().' est déjà installé avec une version supérieure'
										];
									}
								}

								if (($cmp = version_compare($nf_version, version_format(NEOFRAG_VERSION))) !== 1)
								{
									dir_copy($dir, $type.'s/'.$name);

									if ($addon = NeoFrag()->$type($name, TRUE))
									{
										$addon->reset();
										
										return [
											'success' => 'Le '.$type.' '.$addon->get_title().' a été '.(empty($update) ? 'installé' : 'mis-à-jour')
										];
									}

									return [
										'danger' => 'Le '.$type.' '.($addon ? $addon->get_title() : $name).' n\'a pas pu être '.(empty($update) ? 'installé' : 'mis-à-jour')
									];
								}
								
								return [
									'danger' => 'Le '.$type.' '.($addon ? $addon->get_title() : $name).' nécessite la version '.$nf_version.' de NeoFrag, veuillez mettre jour votre site'
								];
							}
							
							return [
								'danger' => 'Le composant ne peut pas être installé, veuillez vérifier la présence des numéros de version'
							];
						}
					}
					
					return [
						'danger' => 'Le composant ne peut pas être installé, veuillez vérifier son contenu'
					];
				};

				$types   = ['modules', 'widgets', 'themes'];

				$results = [
					'danger'  => [],
					'success' => [],
					'warning' => []
				];

				if (count($folders) == 1 && !in_array($folder = current($folders), $types))
				{
					$results = array_merge_recursive($results, $install_addon($tmp.'/'.$folder));
				}
				else
				{
					foreach (array_intersect($folders, $types) as $folder)
					{
						foreach (scandir($tmp.'/'.$folder) as $dir)
						{
							if (!in_array($dir, ['.', '..']) && is_dir($dir = $tmp.'/'.$folder.'/'.$dir))
							{
								$results = array_merge_recursive($results, $install_addon($dir, substr(ucfirst($folder), 0, -1)));
							}
						}
					}
				}

				$this->extension('json');

				dir_remove($tmp);

				return array_filter($results);
			}
			
			return [
				'danger' => ['Erreur de transfert vers le serveur']
			];
		}
		
		return [
			'danger' => [$this->lang('zip_file_required')]
		];
	}
	
	private function _modules_list()
	{
		return $this->panel()
					->heading('Liste des modules', 'fa-edit')
					->body($this->view('modules'), FALSE);
	}
	
	private function _themes_list()
	{
		return $this->panel()
					->heading('Liste des thèmes', 'fa-tint')
					->body($this->view('themes'));
	}
	
	private function _widgets_list()
	{
		return $this->panel()
					->heading('Liste des widgets', 'fa-cubes')
					->body($this->view('widgets'), FALSE);
	}
	
	private function _languages_list()
	{
		return $this->panel()
					->heading('Liste des langues', 'fa-book')
					->body($this->view('languages', [
						'languages' => $this->addons->get_languages()
					]), FALSE);
	}
	
	private function _authenticators_list()
	{
		return $this->panel()
					->heading('Liste des authentificateurs', 'fa-user-circle')
					->body($this->view('authenticators', [
						'authenticators' => $this->addons->get_authenticators(TRUE)
					]), FALSE);
	}

	/*private function _smileys_list()
	{
		return array(
			'title' => 'Liste des smileys',
			'icon'  => 'fa-smile-o'
		);
	}
	
	private function _bbcodes_list()
	{
		return array(
			'title' => 'Liste des BBcodes',
			'icon'  => 'fa-code'
		);
	}*/

	public function _theme_activation($theme)
	{
		$this	->extension('json')
				->config('nf_default_theme', $theme->name);
		
		return [
			'success' => 'Le thème '.$theme->get_title().' a été activé'
		];
	}

	public function _theme_reset($theme)
	{
		$theme->reset()->extension('json');
		
		return [
			'success' => 'Le thème '.$theme->get_title().' a été réinstallé par défaut'
		];
	}

	public function _theme_settings($controller)
	{
		return $controller->index();
	}
	
	public function _language_sort($language, $position)
	{
		$languages = [];
		
		foreach ($this->db->select('code')->from('nf_settings_languages')->where('code !=', $language)->order_by('order')->get() as $code)
		{
			$languages[] = $code;
		}
		
		foreach (array_merge(array_slice($languages, 0, $position, TRUE), [$language], array_slice($languages, $position, NULL, TRUE)) as $order => $code)
		{
			$this->db	->where('code', $code)
						->update('nf_settings_languages', [
							'order' => $order
						]);
		}
	}

	public function _authenticator_sort($auth, $position)
	{
		$authenticators = [];

		foreach ($this->db->select('name')->from('nf_settings_authenticators')->where('name !=', $auth)->order_by('order')->get() as $name)
		{
			$authenticators[] = $name;
		}

		foreach (array_merge(array_slice($authenticators, 0, $position, TRUE), [$auth], array_slice($authenticators, $position, NULL, TRUE)) as $order => $name)
		{
			$this->db	->where('name', $name)
						->update('nf_settings_authenticators', [
							'order' => $order
						]);
		}
	}

	public function _authenticator_admin($authenticator)
	{
		return $authenticator->admin();
	}

	public function _authenticator_update($authenticator, $settings)
	{
		$authenticator->update($settings);
	}
}
