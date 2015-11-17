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

class m_settings_c_admin_ajax extends Controller_Module
{
	public function maintenance()
	{
		$this->config('nf_maintenance', (bool)post('closed'), 'bool');
		
		return (int)$this->config->nf_maintenance;
	}
	
	public function _theme_activation($theme)
	{
		$this->config('nf_default_theme', $theme);
		
		return $theme;
	}
	
	public function _theme_installation()
	{
		$this->extension('json');
		
		if (!empty($_FILES['theme']) && extension($_FILES['theme']['name']) == 'zip')
		{
			if ($zip = zip_open($_FILES['theme']['tmp_name']))
			{
				$theme_name = NULL;
				
				while ($zip_entry = zip_read($zip))
				{
					$entry_name = zip_entry_name($zip_entry);
					$is_dir     = substr($entry_name, -1) == '/';
					
					if (is_null($theme_name) && $is_dir)
					{
						$theme_name = substr($entry_name, 0, -1);
					}
					
					if ($theme_name && strpos($entry_name, $theme_name.'/') === 0)
					{
						if ($is_dir)
						{
							mkdir('./themes/'.$entry_name, 0777, TRUE);
						}
						else if (zip_entry_open($zip, $zip_entry, 'r'))
						{
							$content = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
							
							if ($entry_name == $theme_name.'/'.$theme_name.'.php' && file_exists('./themes/'.$entry_name))
							{
								if (preg_match('/\$version[ \t]*?=[ \t]*?([\'"])(.+?)\1;/', $content, $match))
								{
									$old_version = preg_replace('/[^\d.]/', '', $this->load->theme($theme_name, FALSE)->version);
									$new_version = preg_replace('/[^\d.]/', '', $match[2]);
									
									if ($cmp = version_compare($new_version, $old_version))
									{
										$update = TRUE;
									}
									else
									{
										zip_entry_close($zip_entry);
										
										return json_encode(array(
											'error' => $this($cmp == 0 ? 'already_installed_version' : 'not_newer_installed_version')
										));
									}
								}
							}
							
							file_put_contents('./themes/'.$entry_name, $content);
							
							zip_entry_close($zip_entry);
						}
					}
				}

				zip_close($zip);
				
				if ($theme_name && ($theme = $this->load->theme($theme_name, FALSE)))
				{
					if (empty($update))
					{
						$theme->uninstall()->install();
					}
					else
					{
						$this->db->insert('nf_settings_addons', array(
							'name'   => $theme_name,
							'type'   => 'theme',
							'enable' => TRUE
						));
					}

					return json_encode(array(
						'success' => TRUE
					));
				}
				else
				{
					if ($theme_name)
					{
						rmdir_all('./themes/'.$theme_name);
					}
					
					return json_encode(array(
						'error' => $this('error_theme_install')
					));
				}
			}
		}
		
		return json_encode(array(
			'error' => $this('zip_file_required')
		));
	}
	
	public function _theme_reset($theme_name)
	{
		if ($theme = $this->load->theme($theme_name, FALSE))
		{
			$theme->uninstall()->install();
		}
	}
	
	public function _theme_delete($theme_name)
	{
		if ($theme = $this->load->theme($theme_name, FALSE))
		{
			$theme->uninstall();
		}
		
		rmdir_all('./themes/'.$theme_name);
		
		return $theme_name;
	}
	
	public function _theme_internal($controller)
	{
		return $controller->index();
	}
}

/*
NeoFrag Alpha 0.1.1
./neofrag/modules/settings/controllers/admin_ajax.php
*/